<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Clear tables in the right order (child tables first)
        $tables = [
            'event_speaker',
            'event_registrations',
            'waitlists',
            'event_requests',
            'speakers',
            'events',
            'venues',
            'buildings',
            'campuses',
            'user_notifications',
            'notifications',
            'announcement_views',
            'announcements',
            'feedback',
            'users',
            'role_permission',
            'permissions',
            'roles'
        ];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
            }
        }
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Now run seeders
        $this->call([
            CampusSeeder::class,
            BuildingSeeder::class,
            VenueSeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
            SpeakerSeeder::class,
            EventSeeder::class,
        ]);
    }
}