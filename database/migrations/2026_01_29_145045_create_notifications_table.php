<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->enum('type', ['announcement', 'event', 'system', 'alert', 'info', 'warning', 'success'])->default('info');
            $table->json('data')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('action_url')->nullable();
            $table->string('action_text')->nullable();
            $table->integer('priority')->default(0);
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_public')->default(false);
            $table->timestamps();
            
            $table->index('type');
            $table->index('priority');
            $table->index('created_by');
            $table->index(['scheduled_at', 'expires_at']);
        });

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
            $table->index('read_at');
            $table->index('dismissed_at');
            $table->index(['user_id', 'read_at', 'dismissed_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_notifications');
        Schema::dropIfExists('notifications');
    }
};