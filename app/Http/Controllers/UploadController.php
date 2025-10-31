<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Services\ErrorHandlerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        // Increase execution time for large file uploads
        set_time_limit(0);
        
        // Increase memory limit for large files
        ini_set('memory_limit', '1024M');
        
        \Log::info('Upload request received', [
            'files_count' => $request->hasFile('files') ? count($request->file('files')) : 0,
            'upload_type' => $request->input('upload_type'),
            'user_id' => auth()->id(),
            'total_size' => $request->hasFile('files') ? array_sum(array_map(fn($file) => $file->getSize(), $request->file('files'))) : 0
        ]);

        $request->validate([
            'files' => 'required|array|max:50', // Limit to 50 files per request for bulk uploads
            'files.*' => 'file|max:512000', // 500MB max per file (512MB in KB)
            'upload_type' => 'required|in:files,folders',
            'folder_id' => 'nullable|exists:folders,id',
            'target_folder' => 'nullable|in:documents,pictures,videos,music,other',
            'chunk_index' => 'nullable|integer|min:0',
            'total_chunks' => 'nullable|integer|min:1'
        ]);

        try {
            $uploadedFiles = [];
            $uploadType = $request->input('upload_type');
            $userId = auth()->id();
            $user = auth()->user();

            // Check total file size before processing
            $totalFileSize = 0;
            foreach ($request->file('files') as $file) {
                $totalFileSize += $file->getSize();
            }

            // Check if user has enough storage
            if (!$user->hasEnoughStorage($totalFileSize)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not enough storage space. You have ' . $user->formatStorageSize($user->remaining_storage) . ' remaining out of ' . $user->formatStorageSize($user->storage_limit) . ' total.'
                ], 400);
            }

            foreach ($request->file('files') as $file) {
                $fileSizeMB = round($file->getSize() / 1024 / 1024, 2);
                \Log::info('Processing file', [
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'size_mb' => $fileSizeMB,
                    'mime_type' => $file->getMimeType(),
                    'is_large_file' => $file->getSize() > 100 * 1024 * 1024
                ]);

                // Generate unique filename
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $filename = Str::uuid() . '.' . $extension;
                
                // Store file in storage/app/uploads/{user_id}/
                $path = $file->storeAs("uploads/{$userId}", $filename, 'public');
                
                \Log::info('File stored', ['path' => $path]);
                
                // Create file record in database
                $fileRecord = File::create([
                    'original_name' => $originalName,
                    'stored_name' => $filename,
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'file_type' => $this->getFileTypeFromMime($file->getMimeType()),
                    'folder_type' => $this->getFolderTypeFromMime($file->getMimeType()),
                    'user_id' => $userId,
                    'folder_id' => $request->input('folder_id'),
                    'target_folder' => $request->input('target_folder'),
                ]);
                
                // Update user's storage usage
                $user->addStorageUsage($file->getSize());
                
                $uploadedFiles[] = $fileRecord;
            }

            \Log::info('Upload completed successfully', ['files_count' => count($uploadedFiles)]);

            return response()->json([
                'success' => true,
                'message' => 'Files uploaded successfully',
                'files' => $uploadedFiles,
                'chunk_index' => $request->input('chunk_index'),
                'total_chunks' => $request->input('total_chunks'),
                'is_complete' => !$request->has('chunk_index') || $request->input('chunk_index') >= $request->input('total_chunks') - 1
            ]);

        } catch (\Exception $e) {
            $errorHandler = new ErrorHandlerService();
            return $errorHandler->createErrorResponse($e, $request);
        }
    }

    private function getFileTypeFromMime(string $mime): string
    {
        if (str_starts_with($mime, 'image/')) {
            return 'image';
        } elseif (str_starts_with($mime, 'video/')) {
            return 'video';
        } elseif (str_starts_with($mime, 'audio/')) {
            return 'audio';
        } elseif (in_array($mime, [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/plain',
            'text/csv'
        ])) {
            return 'document';
        }
        
        return 'file';
    }

    private function getFolderTypeFromMime(string $mime): string
    {
        $fileType = $this->getFileTypeFromMime($mime);
        
        switch ($fileType) {
            case 'image':
                return 'pictures';
            case 'video':
                return 'videos';
            case 'audio':
                return 'music';
            case 'document':
                return 'documents';
            default:
                return 'my-files';
        }
    }
}