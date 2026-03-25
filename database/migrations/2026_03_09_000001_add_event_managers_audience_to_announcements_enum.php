<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE announcements
            MODIFY COLUMN audience ENUM('all','students','faculty','staff','event_managers','specific')
            NOT NULL DEFAULT 'all'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("UPDATE announcements SET audience = 'all' WHERE audience = 'event_managers'");

        DB::statement("
            ALTER TABLE announcements
            MODIFY COLUMN audience ENUM('all','students','faculty','staff','specific')
            NOT NULL DEFAULT 'all'
        ");
    }
};

