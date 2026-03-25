<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin
        $superAdminRole = Role::where('slug', 'super-admin')->first();
        User::firstOrCreate(
            ['email' => 'superadmin@ju.edu.et'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'role_id' => $superAdminRole->id,
            ]
        );

        // Create Admin
        $adminRole = Role::where('slug', 'admin')->first();
        User::firstOrCreate(
            ['email' => 'admin@ju.edu.et'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password123'),
                'role_id' => $adminRole->id,
            ]
        );

        // Create Event Manager
        $eventManagerRole = Role::where('slug', 'event-manager')->first();
        User::firstOrCreate(
            ['email' => 'events@ju.edu.et'],
            [
                'name' => 'Event Manager',
                'password' => Hash::make('password123'),
                'role_id' => $eventManagerRole->id,
            ]
        );

        // Create Faculty
        $facultyRole = Role::where('slug', 'faculty')->first();
        User::firstOrCreate(
            ['email' => 'alemayehu@ju.edu.et'],
            [
                'name' => 'Dr. Alemayehu',
                'password' => Hash::make('password123'),
                'role_id' => $facultyRole->id,
            ]
        );

        // Create Student
        $studentRole = Role::where('slug', 'student')->first();
        User::firstOrCreate(
            ['email' => 'student@ju.edu.et'],
            [
                'name' => 'Student User',
                'password' => Hash::make('password123'),
                'role_id' => $studentRole->id,
            ]
        );
        
        echo "Users created/updated successfully!\n";
        echo "Super Admin: superadmin@ju.edu.et / password123\n";
        echo "Admin: admin@ju.edu.et / password123\n";
        echo "Event Manager: events@ju.edu.et / password123\n";
        echo "Faculty: alemayehu@ju.edu.et / password123\n";
        echo "Student: student@ju.edu.et / password123\n";
    }
}