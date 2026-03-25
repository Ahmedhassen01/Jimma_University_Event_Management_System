<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('venue');
            $table->string('campus')->nullable();
            $table->string('event_type'); // academic, cultural, sports, conference, workshop
            $table->string('organizer');
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('featured_image')->nullable();
            $table->integer('max_attendees')->nullable();
            $table->integer('registered_attendees')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_public')->default(true);
            $table->boolean('requires_registration')->default(false);
            $table->string('registration_link')->nullable();
            $table->json('tags')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};