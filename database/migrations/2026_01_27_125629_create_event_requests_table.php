<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->dateTime('proposed_start_date');
            $table->dateTime('proposed_end_date');
            $table->string('proposed_venue');
            $table->string('proposed_campus')->nullable();
            $table->string('event_type');
            $table->string('organizer_name');
            $table->string('organizer_email');
            $table->string('organizer_phone')->nullable();
            $table->integer('expected_attendees')->nullable();
            $table->text('additional_requirements')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected, cancelled
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('review_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('event_id')->nullable()->constrained()->onDelete('set null'); // If approved, link to created event
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_requests');
    }
};