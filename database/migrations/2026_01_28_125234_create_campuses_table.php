<?php
// database/migrations/2024_01_01_000001_create_campuses_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('campuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('campus_id')->constrained()->onDelete('cascade');
            $table->string('code')->nullable(); // Building code like "B1", "SCI-01"
            $table->text('description')->nullable();
            $table->integer('floors')->default(1);
            $table->string('contact_person')->nullable();
            $table->string('contact_phone')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('venues', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('building_id')->constrained()->onDelete('cascade');
            $table->string('type'); // classroom, auditorium, hall, lab, sports_complex, etc.
            $table->integer('capacity')->default(50);
            $table->text('description')->nullable();
            $table->json('amenities')->nullable(); // projector, wifi, sound_system, etc.
            $table->decimal('booking_fee', 10, 2)->nullable();
            $table->boolean('is_available')->default(true);
            $table->boolean('requires_approval')->default(false);
            $table->json('available_hours')->nullable(); // JSON of available time slots
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venues');
        Schema::dropIfExists('buildings');
        Schema::dropIfExists('campuses');
    }
};