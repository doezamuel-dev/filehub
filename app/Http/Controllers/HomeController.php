<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function show($page)
    {
        // Debug: Log the page being requested
        \Log::info('HomeController::show called with page: ' . $page);
        
        // Define available home pages and their display names
        $pages = [
            'recent-files' => 'Recent Files',
            'shared-with-me' => 'Shared with Me',
            'starred' => 'Starred',
            'trash' => 'Trash'
        ];

        // Check if the requested page exists
        if (!array_key_exists($page, $pages)) {
            abort(404, 'Page not found');
        }

        $pageName = $pages[$page];
        
        // Fetch files based on page type
        $query = File::where('user_id', auth()->id());

        switch ($page) {
            case 'recent-files':
                $query->where('is_trashed', false)
                      ->whereNotNull('last_accessed_at')
                      ->orderBy('last_accessed_at', 'desc');
                break;
            case 'shared-with-me':
                // Show files that are shared with the current user (transferred files)
                $query->where('user_id', auth()->id())
                      ->where('is_shared', true)
                      ->where('is_trashed', false)
                      ->whereNull('folder_id') // Only show files not in user-created folders
                      ->orderBy('created_at', 'desc');
                break;
            case 'starred':
                $query->where('is_starred', true)
                      ->where('is_trashed', false)
                      ->orderBy('created_at', 'desc');
                break;
            case 'trash':
                // For trash, show all trashed files regardless of folder assignment
                $query->where('is_trashed', true)
                      ->orderBy('updated_at', 'desc');
                break;
        }

        $files = $query->get();
        
        // Debug: Log the number of files found
        \Log::info('HomeController::show found ' . $files->count() . ' files for page: ' . $page);
        
        return view('home-page', [
            'page' => $page,
            'pageName' => $pageName,
            'files' => $files
        ]);
    }
}