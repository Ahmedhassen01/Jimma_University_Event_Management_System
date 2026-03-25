<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('notifications', 'created_by')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->foreignId('created_by')->nullable()->after('type')->constrained('users')->onDelete('set null');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('notifications', 'created_by')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->dropForeign(['created_by']);
                $table->dropColumn('created_by');
            });
        }
    }
};