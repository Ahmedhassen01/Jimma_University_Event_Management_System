<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Permission;
use App\Models\Role;

return new class extends Migration
{
    public function up()
    {
        $permissions = [
            ['name' => 'View Dashboard', 'slug' => 'view_dashboard', 'description' => 'Access admin dashboard'],
            ['name' => 'View Users', 'slug' => 'view_users', 'description' => 'View users list'],
            ['name' => 'View Roles', 'slug' => 'view_roles', 'description' => 'View roles list'],
            ['name' => 'View Permissions', 'slug' => 'view_permissions', 'description' => 'View permissions list'],
            ['name' => 'Create Users', 'slug' => 'create_users', 'description' => 'Create new users'],
            ['name' => 'Create Roles', 'slug' => 'create_roles', 'description' => 'Create new roles'],
            ['name' => 'Create Permissions', 'slug' => 'create_permissions', 'description' => 'Create new permissions'],
            ['name' => 'Manage Settings', 'slug' => 'manage_settings', 'description' => 'Manage system settings'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }

        // Assign to super-admin
        $superAdmin = Role::where('slug', 'super-admin')->first();
        if ($superAdmin) {
            $allPermissions = Permission::all();
            $superAdmin->permissions()->syncWithoutDetaching($allPermissions->pluck('id'));
        }

        // Assign to admin
        $admin = Role::where('slug', 'admin')->first();
        if ($admin) {
            $adminPermissions = Permission::whereIn('slug', [
                'view_dashboard', 'view_users', 'view_roles', 'view_permissions',
                'create_users', 'create_roles', 'create_permissions', 'manage_settings'
            ])->get();
            $admin->permissions()->syncWithoutDetaching($adminPermissions->pluck('id'));
        }
    }

    public function down()
    {
        Permission::whereIn('slug', [
            'view_dashboard', 'view_users', 'view_roles', 'view_permissions',
            'create_users', 'create_roles', 'create_permissions', 'manage_settings'
        ])->delete();
    }
};