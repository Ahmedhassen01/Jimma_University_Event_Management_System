<?php
// database/migrations/2024_01_01_000001_add_speaker_fields_to_events_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'speaker_names')) {
                $table->json('speaker_names')->nullable()->after('organizer');
            }
            if (!Schema::hasColumn('events', 'speaker_bios')) {
                $table->json('speaker_bios')->nullable()->after('speaker_names');
            }
            if (!Schema::hasColumn('events', 'speaker_photos')) {
                $table->json('speaker_photos')->nullable()->after('speaker_bios');
            }
            if (!Schema::hasColumn('events', 'speaker_titles')) {
                $table->json('speaker_titles')->nullable()->after('speaker_photos');
            }
            if (!Schema::hasColumn('events', 'speaker_organizations')) {
                $table->json('speaker_organizations')->nullable()->after('speaker_titles');
            }
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'speaker_names',
                'speaker_bios',
                'speaker_photos',
                'speaker_titles',
                'speaker_organizations'
            ]);
        });
    }
};