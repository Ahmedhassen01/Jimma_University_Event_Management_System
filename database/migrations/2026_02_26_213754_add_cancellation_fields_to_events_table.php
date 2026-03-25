<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Check if columns don't exist before adding
            if (!Schema::hasColumn('events', 'is_cancelled')) {
                $table->boolean('is_cancelled')->default(false)->after('is_public');
            }
            
            if (!Schema::hasColumn('events', 'cancellation_reason')) {
                $table->text('cancellation_reason')->nullable()->after('is_cancelled');
            }
            
            if (!Schema::hasColumn('events', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable()->after('cancellation_reason');
            }
            
            if (!Schema::hasColumn('events', 'cancelled_by')) {
                $table->foreignId('cancelled_by')->nullable()->constrained('users')->after('cancelled_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Drop foreign key first
            if (Schema::hasColumn('events', 'cancelled_by')) {
                $table->dropForeign(['cancelled_by']);
            }
            
            // Drop columns
            $columns = ['is_cancelled', 'cancellation_reason', 'cancelled_at', 'cancelled_by'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('events', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};