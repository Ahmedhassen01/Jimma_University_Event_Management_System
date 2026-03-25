<?php
// database/migrations/2024_01_01_000002_create_event_registrations_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('registration_number')->unique()->nullable();
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'waitlisted'])->default('pending');
            $table->integer('guest_count')->default(1);
            $table->text('additional_info')->nullable(); // Dietary restrictions, special needs, etc.
            $table->timestamp('registration_date')->useCurrent();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->boolean('attended')->default(false);
            $table->timestamp('check_in_time')->nullable();
            $table->text('notes')->nullable(); // Admin notes
            $table->timestamps();
            
            $table->unique(['event_id', 'user_id']); // Prevent duplicate registrations
        });

        Schema::create('waitlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('position');
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamp('notified_at')->nullable();
            $table->timestamp('converted_at')->nullable(); // When moved from waitlist to registration
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['event_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waitlists');
        Schema::dropIfExists('event_registrations');
    }
};