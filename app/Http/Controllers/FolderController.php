<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    public function index()
    {
        $folders = Folder::where('user_id', auth()->id())
            ->where('is_trashed', false)
            ->orderBy('created_at', 'desc')
            ->get();

        // If it's an AJAX request, return JSON
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'folders' => $folders->map(function($folder) {
                    return [
                        'id' => $folder->id,
                        'name' => $folder->name,
                        'created_at' => $folder->created_at->diffForHumans()
                    ];
                })
            ]);
        }

        return view('folders.index', [
            'folders' => $folders,
        ]);
    }
    public function show($folder)
    {
        // Check if it's a numeric ID (user-created folder) or a string (system folder)
        if (is_numeric($folder)) {
            // User-created folder
            $folderModel = Folder::where('id', $folder)
                ->where('user_id', auth()->id())
                ->where('is_trashed', false)
                ->firstOrFail();

            $files = File::where('user_id', auth()->id())
                ->where('folder_id', $folder)
                ->where('is_trashed', false)
                ->orderBy('created_at', 'desc')
                ->get();

            return view('folder', [
                'folder' => $folderModel,
                'folderName' => $folderModel->name,
                'files' => $files,
                'isUserFolder' => true
            ]);
        } else {
            // System folder (my-files, documents, etc.)
            $folders = [
                'my-files' => 'My Files',
                'documents' => 'Documents',
                'pictures' => 'Pictures',
                'videos' => 'Videos',
                'music' => 'Music'
            ];

            if (!array_key_exists($folder, $folders)) {
                abort(404, 'Folder not found');
            }

            $folderName = $folders[$folder];
            
            // Fetch files for this folder
            $query = File::where('user_id', auth()->id())
                        ->where('is_trashed', false);

            // Filter by folder type - but only show files that are NOT in user-created folders
            if ($folder === 'documents') {
                $query->where('folder_type', 'documents')
                      ->whereNull('folder_id'); // Only show documents not in user folders
            } elseif ($folder === 'pictures') {
                $query->where('folder_type', 'pictures')
                      ->whereNull('folder_id'); // Only show pictures not in user folders
            } elseif ($folder === 'videos') {
                $query->where('folder_type', 'videos')
                      ->whereNull('folder_id'); // Only show videos not in user folders
            } elseif ($folder === 'music') {
                $query->where('folder_type', 'music')
                      ->whereNull('folder_id'); // Only show music not in user folders
            } else {
                // For 'my-files', show all files that are not in user-created folders
                $query->whereNull('folder_id');
            }

            $files = $query->orderBy('created_at', 'desc')->get();
            
            return view('folder', [
                'folder' => $folder,
                'folderName' => $folderName,
                'files' => $files,
                'isUserFolder' => false
            ]);
        }
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|min:1'
        ]);

        $folder = Folder::create([
            'name' => $request->name,
            'user_id' => auth()->id(),
            'parent_id' => null, // Root level folder
        ]);

        return response()->json([
            'success' => true,
            'message' => "Folder '{$folder->name}' created successfully",
            'folder' => $folder
        ]);
    }

    public function delete($folder)
    {
        $folderModel = Folder::where('id', $folder)
            ->where('user_id', auth()->id())
            ->where('is_trashed', false)
            ->firstOrFail();

        // Move folder to trash
        $folderModel->moveToTrash();

        // Also move all files in this folder to trash
        File::where('folder_id', $folder)
            ->where('user_id', auth()->id())
            ->update(['is_trashed' => true]);

        return response()->json([
            'success' => true,
            'message' => "Folder '{$folderModel->name}' moved to trash successfully"
        ]);
    }

    public function share(Request $request, $folder)
    {
        $folderModel = Folder::where('id', $folder)
            ->where('user_id', auth()->id())
            ->where('is_trashed', false)
            ->firstOrFail();

        $request->validate([
            'email' => 'required|email'
        ]);

        // Find the user to share with
        $userToShare = \App\Models\User::where('email', $request->email)->first();
        
        if (!$userToShare) {
            return response()->json([
                'success' => false,
                'message' => 'User not found with that email address'
            ], 400);
        }

        // Don't allow sharing with yourself
        if ($userToShare->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot share a folder with yourself'
            ], 400);
        }

        // Create notification for the user being shared with
        NotificationService::createFolderSharedNotification($folderModel, auth()->user(), $userToShare);

        return response()->json([
            'success' => true,
            'message' => "Folder '{$folderModel->name}' shared with {$userToShare->name} ({$userToShare->email}) successfully"
        ]);
    }
}