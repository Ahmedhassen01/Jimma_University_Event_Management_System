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
        // Create view_dashboard permission if it doesn't exist
        $dashboardPermission = Permission::updateOrCreate(
            ['slug' => 'view_dashboard'],
            [
                'name' => 'View Dashboard',
                'description' => 'Access to admin dashboard'
            ]
        );

        // Get all roles
        $superAdmin = Role::where('slug', 'super-admin')->first();
        $admin = Role::where('slug', 'admin')->first();
        $eventManager = Role::where('slug', 'event-manager')->first();

        // Assign to super-admin
        if ($superAdmin) {
            $superAdmin->permissions()->syncWithoutDetaching([$dashboardPermission->id]);
        }

        // Assign to admin
        if ($admin) {
            $admin->permissions()->syncWithoutDetaching([$dashboardPermission->id]);
        }

        // Assign to event manager if they should have it
        if ($eventManager) {
            $eventManager->permissions()->syncWithoutDetaching([$dashboardPermission->id]);
        }

        // Also check for any users with null role and assign them guest role
        $guestRole = Role::where('slug', 'guest')->first();
        if ($guestRole) {
            App\Models\User::whereNull('role_id')->update(['role_id' => $guestRole->id]);
        }
    }

    public function down()
    {
        // Don't delete permissions in down migration
    }
};