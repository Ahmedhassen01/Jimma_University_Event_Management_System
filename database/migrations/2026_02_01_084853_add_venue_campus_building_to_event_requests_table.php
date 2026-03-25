<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_requests', function (Blueprint $table) {
            // Check if columns exist before adding them
            if (!Schema::hasColumn('event_requests', 'venue_id')) {
                $table->foreignId('venue_id')->nullable()->after('proposed_venue')->constrained()->onDelete('set null');
            }
            
            if (!Schema::hasColumn('event_requests', 'campus_id')) {
                $table->foreignId('campus_id')->nullable()->after('venue_id')->constrained()->onDelete('set null');
            }
            
            if (!Schema::hasColumn('event_requests', 'building_id')) {
                $table->foreignId('building_id')->nullable()->after('campus_id')->constrained()->onDelete('set null');
            }
            
            if (!Schema::hasColumn('event_requests', 'alternative_venue')) {
                $table->string('alternative_venue')->nullable()->after('building_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('event_requests', function (Blueprint $table) {
            $table->dropForeign(['venue_id']);
            $table->dropForeign(['campus_id']);
            $table->dropForeign(['building_id']);
            $table->dropColumn(['venue_id', 'campus_id', 'building_id', 'alternative_venue']);
        });
    }
};