<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Add the old columns back if they don't exist
            if (!Schema::hasColumn('events', 'campus')) {
                $table->string('campus')->nullable()->after('venue_id');
            }
            
            if (!Schema::hasColumn('events', 'building')) {
                $table->string('building')->nullable()->after('campus');
            }
            
            if (!Schema::hasColumn('events', 'venue')) {
                $table->string('venue')->nullable()->after('building');
            }
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Don't drop the columns in down() to prevent data loss
        });
    }
};