<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->enum('type', ['event', 'campus', 'general', 'urgent'])->default('general');
            $table->enum('audience', ['all', 'students', 'faculty', 'staff', 'specific'])->default('all');
            $table->json('target_ids')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->integer('views')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['is_published', 'expires_at']);
            $table->index('type');
            $table->index('audience');
        });

        Schema::create('announcement_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('announcement_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('viewed_at')->useCurrent();
            
            $table->unique(['announcement_id', 'user_id']);
            $table->index('viewed_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('announcement_views');
        Schema::dropIfExists('announcements');
    }
};