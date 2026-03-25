<?php
// database/migrations/2024_01_01_add_views_count_to_events.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'views_count')) {
                $table->integer('views_count')->default(0)->after('registered_attendees');
            }
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'views_count')) {
                $table->dropColumn('views_count');
            }
        });
    }
};