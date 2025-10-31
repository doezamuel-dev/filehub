<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});


Route::get('/dashboard', function () {
    return view('filehub');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Upload routes
    Route::post('/upload', [UploadController::class, 'upload'])->name('upload');
    
    // Folder routes
    Route::get('/folder/{folder}', [FolderController::class, 'show'])->name('folder.show');
    Route::get('/folders', [FolderController::class, 'index'])->name('folders.index');
    Route::post('/folders/create', [FolderController::class, 'create'])->name('folders.create');
    Route::delete('/folders/{folder}/delete', [FolderController::class, 'delete'])->name('folders.delete');
    
    // Home submenu routes
    Route::get('/home/{page}', [HomeController::class, 'show'])->name('home.show');
    
    // File operation routes
    Route::get('/files/{file}/view', [FileController::class, 'view'])->name('files.view');
    Route::get('/files/{file}/download', [FileController::class, 'download'])->name('files.download');
    Route::post('/files/{file}/share', [FileController::class, 'share'])->name('files.share');
    Route::post('/files/{file}/unshare', [FileController::class, 'unshare'])->name('files.unshare');
    Route::get('/files/{file}/shared-users', [FileController::class, 'getSharedUsers'])->name('files.shared-users');
    Route::post('/files/{file}/link', [FileController::class, 'generateLink'])->name('files.link');
    
    // File transfer routes
    Route::get('/api/user-files', [FileController::class, 'apiIndex'])->name('api.user-files');
    Route::post('/files/transfer', [FileController::class, 'transfer'])->name('files.transfer');
    Route::post('/files/{file}/move', [FileController::class, 'move'])->name('files.move');

// API routes for share functionality
Route::get('/api/files', [FileController::class, 'apiIndex'])->name('api.files.index');
Route::post('/folders/{folder}/share', [FolderController::class, 'share'])->name('folders.share');

// Search API route
Route::get('/api/search', [App\Http\Controllers\SearchController::class, 'search'])->name('api.search');

// Notifications routes
Route::get('/notifications', [App\Http\Controllers\NotificationsController::class, 'index'])->name('notifications.index');
Route::post('/notifications/{notification}/mark-read', [App\Http\Controllers\NotificationsController::class, 'markAsRead'])->name('notifications.mark-read');
Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationsController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
Route::get('/notifications/unread-count', [App\Http\Controllers\NotificationsController::class, 'getUnreadCount'])->name('notifications.unread-count');

    // Help route
    Route::get('/help', [App\Http\Controllers\HelpController::class, 'index'])->name('help.index');
    Route::delete('/files/{file}/delete', [FileController::class, 'delete'])->name('files.delete');
    Route::delete('/files/{file}/permanent-delete', [FileController::class, 'permanentDelete'])->name('files.permanent-delete');
    Route::post('/files/{file}/restore', [FileController::class, 'restore'])->name('files.restore');
    Route::post('/files/{file}/star', [FileController::class, 'toggleStar'])->name('files.star');
    
    // Bulk operations
    Route::post('/files/bulk-delete', [FileController::class, 'bulkDelete'])->name('files.bulk-delete');
    Route::post('/files/bulk-permanent-delete', [FileController::class, 'bulkPermanentDelete'])->name('files.bulk-permanent-delete');
    
    // Public file sharing
    Route::get('/share/{token}', [FileController::class, 'publicDownload'])->name('files.share-link');
});

require __DIR__.'/auth.php';
