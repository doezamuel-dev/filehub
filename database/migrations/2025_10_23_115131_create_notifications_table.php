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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('type'); // 'file_shared', 'folder_shared', 'file_transferred', 'folder_transferred'
            $table->string('title');
            $table->text('message');
            $table->unsignedBigInteger('related_file_id')->nullable();
            $table->unsignedBigInteger('related_folder_id')->nullable();
            $table->unsignedBigInteger('from_user_id')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('related_file_id')->references('id')->on('files')->onDelete('cascade');
            $table->foreign('related_folder_id')->references('id')->on('folders')->onDelete('cascade');
            $table->foreign('from_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
