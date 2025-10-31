<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'original_name',
        'stored_name',
        'path',
        'mime_type',
        'size',
        'file_type',
        'folder_type',
        'user_id',
        'folder_id',
        'target_folder',
        'is_starred',
        'is_shared',
        'is_trashed',
        'shared_with',
        'share_token',
        'last_accessed_at',
    ];

    protected $casts = [
        'is_starred' => 'boolean',
        'is_shared' => 'boolean',
        'is_trashed' => 'boolean',
        'shared_with' => 'array',
        'last_accessed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function folder(): BelongsTo
    {
        return $this->belongsTo(Folder::class);
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getFileExtensionAttribute(): string
    {
        return pathinfo($this->original_name, PATHINFO_EXTENSION);
    }

    public function getFileTypeFromMime(): string
    {
        $mime = $this->mime_type;
        
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

    public function getFolderTypeFromFileType(): string
    {
        $fileType = $this->getFileTypeFromMime();
        
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

    public function generateShareToken(): string
    {
        if (!$this->share_token) {
            $this->share_token = Str::random(32);
            $this->save();
        }
        
        return $this->share_token;
    }

    public function getDownloadUrl(): string
    {
        return route('files.download', $this->id);
    }

    public function getShareUrl(): string
    {
        return route('files.share', $this->generateShareToken());
    }

    public function getStoragePath(): string
    {
        return Storage::disk('public')->path($this->path);
    }

    public function exists(): bool
    {
        return Storage::disk('public')->exists($this->path);
    }

    public function deleteFile(): bool
    {
        if ($this->exists()) {
            return Storage::disk('public')->delete($this->path);
        }
        
        return true;
    }

    public function moveToTrash(): void
    {
        $this->update(['is_trashed' => true]);
    }

    public function restoreFromTrash(): void
    {
        $this->update(['is_trashed' => false]);
    }

    public function permanentDelete(): bool
    {
        $deleted = $this->deleteFile();
        if ($deleted) {
            $this->delete();
        }
        
        return $deleted;
    }

    public function toggleStar(): void
    {
        $this->update(['is_starred' => !$this->is_starred]);
    }

    public function updateLastAccessed(): void
    {
        $this->update(['last_accessed_at' => now()]);
    }
}