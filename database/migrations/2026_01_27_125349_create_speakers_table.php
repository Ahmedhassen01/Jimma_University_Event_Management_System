<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('speakers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('title')->nullable(); // Dr., Prof., etc.
            $table->string('position')->nullable(); // CEO, Professor, etc.
            $table->string('organization')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('bio')->nullable();
            $table->string('photo')->nullable();
            $table->string('website')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('facebook')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->json('expertise')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Event Speaker Pivot Table
        Schema::create('event_speaker', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('speaker_id')->constrained()->onDelete('cascade');
            $table->string('session_title')->nullable();
            $table->dateTime('session_time')->nullable();
            $table->integer('session_duration')->nullable(); // in minutes
            $table->text('session_description')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_keynote')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_speaker');
        Schema::dropIfExists('speakers');
    }
};