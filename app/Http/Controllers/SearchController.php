<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        if (empty($query) || strlen($query) < 2) {
            return response()->json([
                'success' => true,
                'files' => [],
                'folders' => []
            ]);
        }

        $userId = auth()->id();

        // Search files
        $files = File::where('user_id', $userId)
            ->where('is_trashed', false)
            ->where('original_name', 'LIKE', "%{$query}%")
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($file) {
                return [
                    'id' => $file->id,
                    'original_name' => $file->original_name,
                    'formatted_size' => $this->formatFileSize($file->size),
                    'mime_type' => $file->mime_type,
                    'created_at' => $file->created_at->diffForHumans()
                ];
            });

        // Search folders
        $folders = Folder::where('user_id', $userId)
            ->where('is_trashed', false)
            ->where('name', 'LIKE', "%{$query}%")
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($folder) {
                return [
                    'id' => $folder->id,
                    'name' => $folder->name,
                    'created_at' => $folder->created_at->diffForHumans()
                ];
            });

        return response()->json([
            'success' => true,
            'files' => $files,
            'folders' => $folders
        ]);
    }

    private function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}