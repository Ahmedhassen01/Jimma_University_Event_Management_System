<?php
// database/migrations/2026_02_17_114534_add_missing_columns_to_event_speaker_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_speaker', function (Blueprint $table) {
            // Check and add each column if it doesn't exist
            if (!Schema::hasColumn('event_speaker', 'session_title')) {
                $table->string('session_title')->nullable()->after('speaker_id');
            }
            
            if (!Schema::hasColumn('event_speaker', 'session_time')) {
                $table->dateTime('session_time')->nullable()->after('session_title');
            }
            
            if (!Schema::hasColumn('event_speaker', 'session_duration')) {
                $table->integer('session_duration')->nullable()->after('session_time');
            }
            
            if (!Schema::hasColumn('event_speaker', 'session_description')) {
                $table->text('session_description')->nullable()->after('session_duration');
            }
            
            if (!Schema::hasColumn('event_speaker', 'order')) {
                $table->integer('order')->default(0)->after('session_description');
            }
            
            if (!Schema::hasColumn('event_speaker', 'is_keynote')) {
                $table->boolean('is_keynote')->default(false)->after('order');
            }
            
            if (!Schema::hasColumn('event_speaker', 'is_moderator')) {
                $table->boolean('is_moderator')->default(false)->after('is_keynote');
            }
            
            if (!Schema::hasColumn('event_speaker', 'is_panelist')) {
                $table->boolean('is_panelist')->default(false)->after('is_moderator');
            }
            
            if (!Schema::hasColumn('event_speaker', 'custom_data')) {
                $table->json('custom_data')->nullable()->after('is_panelist');
            }
        });

        // Check and add foreign keys using raw SQL or Schema builder
        try {
            // Get foreign key information from information_schema
            $databaseName = DB::connection()->getDatabaseName();
            
            // Check if event_id foreign key exists
            $fkEventExists = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = ? 
                AND TABLE_NAME = 'event_speaker' 
                AND COLUMN_NAME = 'event_id' 
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ", [$databaseName]);

            if (empty($fkEventExists)) {
                // Add foreign key for event_id
                Schema::table('event_speaker', function (Blueprint $table) {
                    $table->foreign('event_id')
                          ->references('id')
                          ->on('events')
                          ->onDelete('cascade');
                });
            }

            // Check if speaker_id foreign key exists
            $fkSpeakerExists = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = ? 
                AND TABLE_NAME = 'event_speaker' 
                AND COLUMN_NAME = 'speaker_id' 
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ", [$databaseName]);

            if (empty($fkSpeakerExists)) {
                // Add foreign key for speaker_id
                Schema::table('event_speaker', function (Blueprint $table) {
                    $table->foreign('speaker_id')
                          ->references('id')
                          ->on('speakers')
                          ->onDelete('cascade');
                });
            }
        } catch (\Exception $e) {
            // If we can't check information_schema, try to add the foreign keys directly
            // They will fail if they already exist, but that's okay
            try {
                Schema::table('event_speaker', function (Blueprint $table) {
                    $table->foreign('event_id')
                          ->references('id')
                          ->on('events')
                          ->onDelete('cascade');
                });
            } catch (\Exception $e) {
                // Foreign key probably already exists, ignore
            }

            try {
                Schema::table('event_speaker', function (Blueprint $table) {
                    $table->foreign('speaker_id')
                          ->references('id')
                          ->on('speakers')
                          ->onDelete('cascade');
                });
            } catch (\Exception $e) {
                // Foreign key probably already exists, ignore
            }
        }
    }

    public function down(): void
    {
        Schema::table('event_speaker', function (Blueprint $table) {
            // Drop foreign keys first
            try {
                $table->dropForeign(['event_id']);
            } catch (\Exception $e) {
                // Foreign key might not exist
            }
            
            try {
                $table->dropForeign(['speaker_id']);
            } catch (\Exception $e) {
                // Foreign key might not exist
            }
            
            // Drop columns that we added
            $columns = [
                'session_title',
                'session_time',
                'session_duration',
                'session_description',
                'order',
                'is_keynote',
                'is_moderator',
                'is_panelist',
                'custom_data'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('event_speaker', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};