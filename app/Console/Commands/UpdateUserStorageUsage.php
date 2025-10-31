<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\File;
use Illuminate\Console\Command;

class UpdateUserStorageUsage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:update-usage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update user storage usage based on their current files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating user storage usage...');

        $users = User::all();
        $updatedCount = 0;

        foreach ($users as $user) {
            // Calculate total storage used by user's files
            $totalStorageUsed = File::where('user_id', $user->id)
                ->where('is_trashed', false)
                ->sum('size');

            // Update user's storage usage
            $user->storage_used = $totalStorageUsed;
            $user->save();

            $updatedCount++;
            $this->line("Updated storage for user {$user->name}: {$user->formatStorageSize($totalStorageUsed)}");
        }

        $this->info("Updated storage usage for {$updatedCount} users.");
    }
}