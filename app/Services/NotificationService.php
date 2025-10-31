<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\File;
use App\Models\Folder;
use App\Models\User;

class NotificationService
{
    public static function createFileSharedNotification(File $file, User $fromUser, User $toUser)
    {
        return Notification::create([
            'user_id' => $toUser->id,
            'type' => 'file_shared',
            'title' => 'File Shared',
            'message' => "{$fromUser->name} shared the file '{$file->original_name}' with you.",
            'related_file_id' => $file->id,
            'from_user_id' => $fromUser->id,
        ]);
    }

    public static function createFolderSharedNotification(Folder $folder, User $fromUser, User $toUser)
    {
        return Notification::create([
            'user_id' => $toUser->id,
            'type' => 'folder_shared',
            'title' => 'Folder Shared',
            'message' => "{$fromUser->name} shared the folder '{$folder->name}' with you.",
            'related_folder_id' => $folder->id,
            'from_user_id' => $fromUser->id,
        ]);
    }

    public static function createFileTransferredNotification(File $file, User $fromUser, User $toUser)
    {
        return Notification::create([
            'user_id' => $toUser->id,
            'type' => 'file_transferred',
            'title' => 'File Transferred',
            'message' => "{$fromUser->name} transferred a copy of '{$file->original_name}' to you.",
            'related_file_id' => $file->id,
            'from_user_id' => $fromUser->id,
        ]);
    }

    public static function createFolderTransferredNotification(Folder $folder, User $fromUser, User $toUser)
    {
        return Notification::create([
            'user_id' => $toUser->id,
            'type' => 'folder_transferred',
            'title' => 'Folder Transferred',
            'message' => "{$fromUser->name} transferred a copy of the folder '{$folder->name}' to you.",
            'related_folder_id' => $folder->id,
            'from_user_id' => $fromUser->id,
        ]);
    }
}
