<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('event_requests', 'manager_viewed_at')) {
                $table->timestamp('manager_viewed_at')->nullable()->after('reviewed_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('event_requests', function (Blueprint $table) {
            if (Schema::hasColumn('event_requests', 'manager_viewed_at')) {
                $table->dropColumn('manager_viewed_at');
            }
        });
    }
};
