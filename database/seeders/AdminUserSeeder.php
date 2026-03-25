<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create permissions
        $permissions = [
            ['name' => 'View Dashboard', 'slug' => 'view_dashboard'],
            ['name' => 'View Users', 'slug' => 'view_users'],
            ['name' => 'Create Users', 'slug' => 'create_users'],
            ['name' => 'Edit Users', 'slug' => 'edit_users'],
            ['name' => 'Delete Users', 'slug' => 'delete_users'],
            ['name' => 'View Roles', 'slug' => 'view_roles'],
            ['name' => 'Create Roles', 'slug' => 'create_roles'],
            ['name' => 'Edit Roles', 'slug' => 'edit_roles'],
            ['name' => 'Delete Roles', 'slug' => 'delete_roles'],
            ['name' => 'View Permissions', 'slug' => 'view_permissions'],
            ['name' => 'Create Permissions', 'slug' => 'create_permissions'],
            ['name' => 'Edit Permissions', 'slug' => 'edit_permissions'],
            ['name' => 'Delete Permissions', 'slug' => 'delete_permissions'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }

        // Create Super Admin role
        $superAdminRole = Role::firstOrCreate(
            ['slug' => 'super-admin'],
            [
                'name' => 'Super Administrator',
                'description' => 'Has full access to all system features'
            ]
        );

        // Assign all permissions to Super Admin
        $allPermissions = Permission::all();
        $superAdminRole->permissions()->sync($allPermissions->pluck('id'));

        // Create Super Admin user
        User::firstOrCreate(
            ['email' => 'superadmin@ju.edu.et'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'role_id' => $superAdminRole->id,
            ]
        );

        echo "Super Admin created:\n";
        echo "Email: superadmin@ju.edu.et\n";
        echo "Password: password123\n";
    }
}