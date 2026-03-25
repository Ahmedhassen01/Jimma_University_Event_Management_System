<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // First, drop existing tables if they exist
        Schema::dropIfExists('user_notifications');
        Schema::dropIfExists('notifications');
        
        // Create notifications table with all required columns
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->enum('type', ['announcement', 'event', 'system', 'alert', 'info', 'warning', 'success'])
                  ->default('announcement');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('action_url')->nullable();
            $table->string('action_text')->nullable();
            $table->tinyInteger('priority')->default(0); // 0=normal, 1=high, 2=urgent
            $table->json('data')->nullable();
            $table->boolean('is_public')->default(false);
            $table->timestamps();
            
            $table->index(['type', 'created_at']);
            $table->index('priority');
        });
        
        // Create user_notifications pivot table
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('notification_id')->constrained()->onDelete('cascade');
            $table->timestamp('read_at')->nullable();
            $table->timestamp('dismissed_at')->nullable();
            $table->boolean('email_sent')->default(false);
            $table->timestamp('email_sent_at')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'notification_id']);
            $table->index(['user_id', 'read_at', 'dismissed_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_notifications');
        Schema::dropIfExists('notifications');
    }
};