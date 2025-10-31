<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Services\ErrorHandlerService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function view(File $file)
    {
        // Check if user has access to this file
        if ($file->user_id !== auth()->id() && !$this->hasSharedAccess($file)) {
            abort(403, 'Access denied');
        }

        // Update last accessed time
        $file->updateLastAccessed();

        // For images, videos, and PDFs, we can display them directly
        if (str_starts_with($file->mime_type, 'image/') || 
            str_starts_with($file->mime_type, 'video/') || 
            $file->mime_type === 'application/pdf') {
            
            return response()->file($file->getStoragePath());
        }

        // For other files, trigger download
        return $this->download($file);
    }

    public function download(File $file)
    {
        // Check if user has access to this file
        if ($file->user_id !== auth()->id() && !$this->hasSharedAccess($file)) {
            abort(403, 'Access denied');
        }

        // Update last accessed time
        $file->updateLastAccessed();

        if (!$file->exists()) {
            abort(404, 'File not found');
        }

        return Storage::disk('public')->download($file->path, $file->original_name);
    }

    public function share(Request $request, File $file)
    {
        // Check if user owns this file
        if ($file->user_id !== auth()->id()) {
            abort(403, 'Access denied');
        }

        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $email = $request->input('email');
        $userToShare = \App\Models\User::where('email', $email)->first();

        // Don't allow sharing with yourself
        if ($userToShare->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot share a file with yourself'
            ], 400);
        }

        // Get current shared users
        $sharedWith = $file->shared_with ?? [];
        
        // Check if already shared with this user
        if (in_array($userToShare->id, $sharedWith)) {
            return response()->json([
                'success' => false,
                'message' => 'File is already shared with this user'
            ], 400);
        }

        // Add user to shared list
        $sharedWith[] = $userToShare->id;
        $file->shared_with = $sharedWith;
        $file->is_shared = true;
        $file->save();

        // Create notification for the user being shared with
        NotificationService::createFileSharedNotification($file, auth()->user(), $userToShare);

        return response()->json([
            'success' => true,
            'message' => "File shared with {$userToShare->name} ({$userToShare->email})"
        ]);
    }

    public function generateLink(File $file)
    {
        // Check if user owns this file
        if ($file->user_id !== auth()->id()) {
            abort(403, 'Access denied');
        }

        $shareToken = $file->generateShareToken();
        $shareUrl = $file->getShareUrl();

        return response()->json([
            'success' => true,
            'share_url' => $shareUrl,
            'share_token' => $shareToken
        ]);
    }

    public function delete(File $file)
    {
        // Check if user owns this file
        if ($file->user_id !== auth()->id()) {
            abort(403, 'Access denied');
        }

        $file->moveToTrash();

        return response()->json([
            'success' => true,
            'message' => 'File moved to trash successfully'
        ]);
    }

    public function permanentDelete(File $file)
    {
        // Check if user owns this file
        if ($file->user_id !== auth()->id()) {
            abort(403, 'Access denied');
        }

        $fileSize = $file->size;
        $deleted = $file->permanentDelete();

        if ($deleted) {
            // Update user's storage usage
            auth()->user()->removeStorageUsage($fileSize);
        }

        return response()->json([
            'success' => $deleted,
            'message' => $deleted ? 'File permanently deleted' : 'Failed to delete file'
        ]);
    }

    public function restore(File $file)
    {
        // Check if user owns this file
        if ($file->user_id !== auth()->id()) {
            abort(403, 'Access denied');
        }

        $file->restoreFromTrash();

        return response()->json([
            'success' => true,
            'message' => 'File restored successfully'
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'file_ids' => 'required|array',
            'file_ids.*' => 'integer|exists:files,id'
        ]);

        $fileIds = $request->input('file_ids');
        $userId = auth()->id();
        
        // Get files that belong to the user
        $files = File::whereIn('id', $fileIds)
                    ->where('user_id', $userId)
                    ->where('is_trashed', false)
                    ->get();

        $deletedCount = 0;
        foreach ($files as $file) {
            $file->moveToTrash();
            $deletedCount++;
        }

        return response()->json([
            'success' => true,
            'message' => "Moved {$deletedCount} files to trash successfully"
        ]);
    }

    public function bulkPermanentDelete(Request $request)
    {
        $request->validate([
            'file_ids' => 'required|array',
            'file_ids.*' => 'integer|exists:files,id'
        ]);

        $fileIds = $request->input('file_ids');
        $userId = auth()->id();
        
        // Get files that belong to the user and are in trash
        $files = File::whereIn('id', $fileIds)
                    ->where('user_id', $userId)
                    ->where('is_trashed', true)
                    ->get();

        $deletedCount = 0;
        $totalSize = 0;
        
        foreach ($files as $file) {
            $totalSize += $file->size;
            if ($file->permanentDelete()) {
                $deletedCount++;
            }
        }

        // Update user's storage usage
        if ($totalSize > 0) {
            auth()->user()->removeStorageUsage($totalSize);
        }

        return response()->json([
            'success' => true,
            'message' => "Permanently deleted {$deletedCount} files successfully"
        ]);
    }

    public function toggleStar(File $file)
    {
        // Check if user has access to this file
        if ($file->user_id !== auth()->id() && !$this->hasSharedAccess($file)) {
            abort(403, 'Access denied');
        }

        $file->toggleStar();

        return response()->json([
            'success' => true,
            'is_starred' => $file->is_starred,
            'message' => $file->is_starred ? 'File starred' : 'File unstarred'
        ]);
    }

    public function move(Request $request, File $file)
    {
        // Check if user owns this file
        if ($file->user_id !== auth()->id()) {
            abort(403, 'Access denied');
        }

        $request->validate([
            'folder_id' => 'nullable|exists:folders,id'
        ]);

        $folderId = $request->input('folder_id');
        
        // If folder_id is provided, verify the folder belongs to the user
        if ($folderId) {
            $folder = \App\Models\Folder::where('id', $folderId)
                ->where('user_id', auth()->id())
                ->where('is_trashed', false)
                ->first();
            
            if (!$folder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Folder not found or access denied'
                ], 404);
            }
        }

        // Update the file's folder_id
        $file->folder_id = $folderId;
        $file->save();

        $message = $folderId ? 
            "File moved to folder successfully" : 
            "File moved to root directory successfully";

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function getSharedUsers(File $file)
    {
        // Check if user owns this file
        if ($file->user_id !== auth()->id()) {
            abort(403, 'Access denied');
        }

        $sharedWith = $file->shared_with ?? [];
        $users = \App\Models\User::whereIn('id', $sharedWith)->get(['id', 'name', 'email']);

        return response()->json([
            'success' => true,
            'users' => $users
        ]);
    }

    public function unshare(Request $request, File $file)
    {
        // Check if user owns this file
        if ($file->user_id !== auth()->id()) {
            abort(403, 'Access denied');
        }

        $request->validate([
            'user_id' => 'required|integer|exists:users,id'
        ]);

        $userIdToRemove = $request->input('user_id');
        $sharedWith = $file->shared_with ?? [];
        
        // Remove user from shared list
        $sharedWith = array_filter($sharedWith, function($id) use ($userIdToRemove) {
            return $id != $userIdToRemove;
        });
        
        $file->shared_with = array_values($sharedWith); // Re-index array
        
        // If no more shared users, set is_shared to false
        if (empty($sharedWith)) {
            $file->is_shared = false;
        }
        
        $file->save();

        $user = \App\Models\User::find($userIdToRemove);

        return response()->json([
            'success' => true,
            'message' => "File access removed for {$user->name} ({$user->email})"
        ]);
    }

    public function publicDownload($token)
    {
        $file = File::where('share_token', $token)->first();

        if (!$file) {
            abort(404, 'File not found');
        }

        if (!$file->exists()) {
            abort(404, 'File not found on disk');
        }

        return Storage::disk('public')->download($file->path, $file->original_name);
    }

    private function hasSharedAccess(File $file): bool
    {
        if (!$file->is_shared) {
            return false;
        }

        // Check if file is shared with current user
        if ($file->shared_with && in_array(auth()->id(), $file->shared_with)) {
            return true;
        }

        return false;
    }

    public function apiIndex()
    {
        $files = File::where('user_id', auth()->id())
            ->where('is_trashed', false)
            // Include all files (both in folders and not in folders)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'files' => $files->map(function($file) {
                return [
                    'id' => $file->id,
                    'original_name' => $file->original_name,
                    'file_type' => $file->file_type,
                    'formatted_size' => $file->formatted_size,
                    'created_at' => $file->created_at->diffForHumans(),
                    'folder_name' => $file->folder ? $file->folder->name : 'Root Directory'
                ];
            })
        ]);
    }

    public function transfer(Request $request)
    {
        $request->validate([
            'file_ids' => 'required|array',
            'file_ids.*' => 'integer|exists:files,id',
            'target_email' => 'required|email|exists:users,email'
        ]);

        $targetUser = \App\Models\User::where('email', $request->target_email)->first();
        
        // Don't allow transferring to yourself
        if ($targetUser->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot transfer files to yourself'
            ], 400);
        }

        $transferredFiles = [];
        $errors = [];

        foreach ($request->file_ids as $fileId) {
            $originalFile = File::where('id', $fileId)
                ->where('user_id', auth()->id())
                ->where('is_trashed', false)
                ->first();

            if (!$originalFile) {
                $errors[] = "File with ID {$fileId} not found or access denied";
                continue;
            }

            try {
                // Create a copy of the file
                $newFileName = \Illuminate\Support\Str::uuid() . '.' . pathinfo($originalFile->original_name, PATHINFO_EXTENSION);
                $newPath = "uploads/{$targetUser->id}/{$newFileName}";
                
                // Copy the physical file
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($originalFile->path)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->copy($originalFile->path, $newPath);
                    
                    // Create new file record for the target user
                    $newFile = File::create([
                        'original_name' => $originalFile->original_name,
                        'stored_name' => $newFileName,
                        'path' => $newPath,
                        'mime_type' => $originalFile->mime_type,
                        'size' => $originalFile->size,
                        'file_type' => $originalFile->file_type,
                        'folder_type' => $originalFile->folder_type,
                        'user_id' => $targetUser->id,
                        'folder_id' => null, // Files transferred are not in folders
                        'is_shared' => true, // Mark as shared
                        'shared_with' => [$targetUser->id], // Share with the target user
                        'is_trashed' => false,
                    ]);

                    $transferredFiles[] = $newFile;
                    
                    // Create notification for the target user
                    NotificationService::createFileTransferredNotification($originalFile, auth()->user(), $targetUser);
                } else {
                    $errors[] = "Physical file not found for {$originalFile->original_name}";
                }
            } catch (\Exception $e) {
                $errors[] = "Failed to transfer {$originalFile->original_name}: " . $e->getMessage();
            }
        }

        if (count($transferredFiles) > 0) {
            $message = count($transferredFiles) . " file(s) transferred successfully to {$targetUser->name}";
            if (count($errors) > 0) {
                $message .= ". " . count($errors) . " file(s) failed to transfer.";
            }
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'transferred_count' => count($transferredFiles),
                'errors' => $errors
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No files were transferred. ' . implode(', ', $errors)
            ], 400);
        }
    }

}