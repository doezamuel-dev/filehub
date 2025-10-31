<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('original_name');
            $table->string('stored_name');
            $table->string('path');
            $table->string('mime_type');
            $table->bigInteger('size');
            $table->string('file_type')->default('file'); // file, folder, image, video, audio, document
            $table->string('folder_type')->nullable(); // my-files, documents, pictures, videos, music
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('is_starred')->default(false);
            $table->boolean('is_shared')->default(false);
            $table->boolean('is_trashed')->default(false);
            $table->string('shared_with')->nullable(); // JSON array of user IDs
            $table->string('share_token')->nullable(); // For public sharing
            $table->timestamp('last_accessed_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'folder_type']);
            $table->index(['user_id', 'is_trashed']);
            $table->index(['user_id', 'is_starred']);
            $table->index('share_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};