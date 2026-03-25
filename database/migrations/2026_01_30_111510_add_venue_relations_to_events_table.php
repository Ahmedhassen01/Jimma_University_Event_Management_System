<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Add foreign key columns
            $table->foreignId('campus_id')->nullable()->after('end_date')
                  ->constrained()->onDelete('set null');
            $table->foreignId('building_id')->nullable()->after('campus_id')
                  ->constrained()->onDelete('set null');
            $table->foreignId('venue_id')->nullable()->after('building_id')
                  ->constrained()->onDelete('set null');
            
            // Remove the old campus and venue columns since we're using relations now
            $table->dropColumn(['campus', 'venue']);
            
            // You may want to keep additional_venue_info column
            // Add it if it doesn't exist
            if (!Schema::hasColumn('events', 'additional_venue_info')) {
                $table->json('additional_venue_info')->nullable()->after('venue_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['campus_id']);
            $table->dropForeign(['building_id']);
            $table->dropForeign(['venue_id']);
            
            // Drop the new columns
            $table->dropColumn(['campus_id', 'building_id', 'venue_id', 'additional_venue_info']);
            
            // Re-add the old columns
            $table->string('campus')->nullable();
            $table->string('venue');
        });
    }
};