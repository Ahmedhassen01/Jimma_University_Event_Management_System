<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('user_notifications')) {
            // Check what columns exist and add missing ones
            if (!Schema::hasColumn('user_notifications', 'read_at')) {
                Schema::table('user_notifications', function (Blueprint $table) {
                    $table->timestamp('read_at')->nullable()->after('notification_id');
                });
            }
            
            if (!Schema::hasColumn('user_notifications', 'created_at')) {
                Schema::table('user_notifications', function (Blueprint $table) {
                    $table->timestamps();
                });
            }
            
            // Remove columns that don't exist in your table
            // (Don't try to drop columns that might be used elsewhere)
        }
    }

    public function down(): void
    {
        // Optional rollback
    }
};