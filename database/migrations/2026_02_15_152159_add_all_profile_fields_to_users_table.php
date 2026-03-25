<?php
// database/migrations/2026_02_15_152000_add_all_profile_fields_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Profile photo
            if (!Schema::hasColumn('users', 'profile_photo')) {
                $table->string('profile_photo')->nullable()->after('remember_token');
            }
            
            // Contact information
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            
            if (!Schema::hasColumn('users', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('phone');
            }
            
            if (!Schema::hasColumn('users', 'office_address')) {
                $table->string('office_address')->nullable()->after('date_of_birth');
            }
            
            // Professional information
            if (!Schema::hasColumn('users', 'expertise')) {
                $table->string('expertise')->nullable()->after('office_address');
            }
            
            if (!Schema::hasColumn('users', 'department')) {
                $table->string('department')->nullable()->after('expertise');
            }
            
            if (!Schema::hasColumn('users', 'faculty')) {
                $table->string('faculty')->nullable()->after('department');
            }
            
            // Student specific fields
            if (!Schema::hasColumn('users', 'student_id')) {
                $table->string('student_id')->nullable()->after('faculty');
            }
            
            if (!Schema::hasColumn('users', 'year_of_study')) {
                $table->string('year_of_study')->nullable()->after('student_id');
            }
            
            // Employee specific fields
            if (!Schema::hasColumn('users', 'employee_id')) {
                $table->string('employee_id')->nullable()->after('year_of_study');
            }
            
            if (!Schema::hasColumn('users', 'position')) {
                $table->string('position')->nullable()->after('employee_id');
            }
            
            // Preferences
            if (!Schema::hasColumn('users', 'newsletter_subscription')) {
                $table->boolean('newsletter_subscription')->default(false)->after('position');
            }
            
            if (!Schema::hasColumn('users', 'email_notifications')) {
                $table->boolean('email_notifications')->default(true)->after('newsletter_subscription');
            }
            
            if (!Schema::hasColumn('users', 'event_reminders')) {
                $table->boolean('event_reminders')->default(true)->after('email_notifications');
            }
            
            if (!Schema::hasColumn('users', 'theme_preference')) {
                $table->string('theme_preference')->default('system')->after('event_reminders');
            }
            
            if (!Schema::hasColumn('users', 'language')) {
                $table->string('language')->default('en')->after('theme_preference');
            }
            
            // Account status
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status')->default('active')->after('language');
            }
            
            if (!Schema::hasColumn('users', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('users', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->constrained('users')->after('approved_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'profile_photo',
                'phone',
                'date_of_birth',
                'office_address',
                'expertise',
                'department',
                'faculty',
                'student_id',
                'year_of_study',
                'employee_id',
                'position',
                'newsletter_subscription',
                'email_notifications',
                'event_reminders',
                'theme_preference',
                'language',
                'status',
                'approved_at',
                'approved_by'
            ];
            
            // Only drop columns that exist
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};