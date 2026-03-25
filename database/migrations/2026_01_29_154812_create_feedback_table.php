<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->enum('type', ['event', 'system', 'general', 'suggestion', 'complaint'])->default('general');
            $table->string('subject')->nullable();
            $table->text('message');
            $table->integer('rating')->nullable()->unsigned();
            $table->enum('status', ['pending', 'reviewed', 'resolved', 'closed'])->default('pending');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->text('admin_notes')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->boolean('allow_contact')->default(false);
            $table->boolean('is_public')->default(false);
            $table->boolean('featured')->default(false);
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('feedback_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feedback_id')->constrained()->onDelete('cascade');
            $table->foreignId('responded_by')->constrained('users')->onDelete('cascade');
            $table->text('message');
            $table->boolean('send_email')->default(false);
            $table->timestamp('email_sent_at')->nullable();
            $table->timestamps();
        });

        Schema::create('feedback_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('feedback_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feedback_id')->constrained()->onDelete('cascade');
            $table->foreignId('feedback_category_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['feedback_id', 'feedback_category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback_category');
        Schema::dropIfExists('feedback_categories');
        Schema::dropIfExists('feedback_responses');
        Schema::dropIfExists('feedback');
    }
};