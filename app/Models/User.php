<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'storage_used',
        'storage_limit',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'storage_used' => 'integer',
            'storage_limit' => 'integer',
        ];
    }

    /**
     * Get the storage usage percentage
     */
    public function getStorageUsagePercentageAttribute(): float
    {
        if ($this->storage_limit == 0) {
            return 0;
        }
        return ($this->storage_used / $this->storage_limit) * 100;
    }

    /**
     * Get the remaining storage in bytes
     */
    public function getRemainingStorageAttribute(): int
    {
        return max(0, $this->storage_limit - $this->storage_used);
    }

    /**
     * Check if user has enough storage for a file
     */
    public function hasEnoughStorage(int $fileSize): bool
    {
        return ($this->storage_used + $fileSize) <= $this->storage_limit;
    }

    /**
     * Add storage usage
     */
    public function addStorageUsage(int $bytes): void
    {
        $this->storage_used += $bytes;
        $this->save();
    }

    /**
     * Remove storage usage
     */
    public function removeStorageUsage(int $bytes): void
    {
        $this->storage_used = max(0, $this->storage_used - $bytes);
        $this->save();
    }

    /**
     * Format storage size for display
     */
    public function formatStorageSize(int $bytes): string
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
