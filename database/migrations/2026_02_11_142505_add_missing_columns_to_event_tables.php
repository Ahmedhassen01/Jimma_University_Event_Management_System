<?php
// database/migrations/2024_01_01_000003_add_missing_columns_to_event_tables.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add cancelled_at to event_requests if not exists
        if (Schema::hasTable('event_requests') && !Schema::hasColumn('event_requests', 'cancelled_at')) {
            Schema::table('event_requests', function (Blueprint $table) {
                $table->timestamp('cancelled_at')->nullable()->after('reviewed_at');
            });
        }

        // Ensure waitlist table has all columns
        if (Schema::hasTable('waitlists')) {
            if (!Schema::hasColumn('waitlists', 'notified_at')) {
                Schema::table('waitlists', function (Blueprint $table) {
                    $table->timestamp('notified_at')->nullable()->after('joined_at');
                });
            }
            
            if (!Schema::hasColumn('waitlists', 'converted_at')) {
                Schema::table('waitlists', function (Blueprint $table) {
                    $table->timestamp('converted_at')->nullable()->after('notified_at');
                });
            }
            
            if (!Schema::hasColumn('waitlists', 'notes')) {
                Schema::table('waitlists', function (Blueprint $table) {
                    $table->text('notes')->nullable()->after('converted_at');
                });
            }
        }

        // Ensure event_registrations table has all columns
        if (Schema::hasTable('event_registrations')) {
            if (!Schema::hasColumn('event_registrations', 'cancelled_at')) {
                Schema::table('event_registrations', function (Blueprint $table) {
                    $table->timestamp('cancelled_at')->nullable()->after('confirmed_at');
                });
            }
            
            if (!Schema::hasColumn('event_registrations', 'cancellation_reason')) {
                Schema::table('event_registrations', function (Blueprint $table) {
                    $table->text('cancellation_reason')->nullable()->after('cancelled_at');
                });
            }
            
            if (!Schema::hasColumn('event_registrations', 'attended')) {
                Schema::table('event_registrations', function (Blueprint $table) {
                    $table->boolean('attended')->default(false)->after('cancellation_reason');
                });
            }
            
            if (!Schema::hasColumn('event_registrations', 'check_in_time')) {
                Schema::table('event_registrations', function (Blueprint $table) {
                    $table->timestamp('check_in_time')->nullable()->after('attended');
                });
            }
            
            if (!Schema::hasColumn('event_registrations', 'notes')) {
                Schema::table('event_registrations', function (Blueprint $table) {
                    $table->text('notes')->nullable()->after('check_in_time');
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('event_requests') && Schema::hasColumn('event_requests', 'cancelled_at')) {
            Schema::table('event_requests', function (Blueprint $table) {
                $table->dropColumn('cancelled_at');
            });
        }

        if (Schema::hasTable('waitlists')) {
            $columns = ['notified_at', 'converted_at', 'notes'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('waitlists', $column)) {
                    Schema::table('waitlists', function (Blueprint $table) use ($column) {
                        $table->dropColumn($column);
                    });
                }
            }
        }

        if (Schema::hasTable('event_registrations')) {
            $columns = ['cancelled_at', 'cancellation_reason', 'attended', 'check_in_time', 'notes'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('event_registrations', $column)) {
                    Schema::table('event_registrations', function (Blueprint $table) use ($column) {
                        $table->dropColumn($column);
                    });
                }
            }
        }
    }
};