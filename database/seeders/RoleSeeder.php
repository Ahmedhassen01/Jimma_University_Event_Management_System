<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Super Administrator',
                'slug' => 'super-admin',
                'description' => 'Has full access to all system features'
            ],
            [
                'name' => 'Administrator',
                'slug' => 'admin',
                'description' => 'Can manage users, roles, and permissions'
            ],
            [
                'name' => 'Event Manager',
                'slug' => 'event-manager',
                'description' => 'Can create and manage events'
            ],
            [
                'name' => 'Faculty',
                'slug' => 'faculty',
                'description' => 'University faculty member'
            ],
            [
                'name' => 'Student',
                'slug' => 'student',
                'description' => 'University student'
            ],
            [
                'name' => 'Guest',
                'slug' => 'guest',
                'description' => 'Limited access user'
            ]
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['slug' => $role['slug']], // Use slug as unique identifier
                $role
            );
        }
    }
}