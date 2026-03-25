<?php
// database/seeders/NotificationPermissionsSeeder.php
namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationPermissionsSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            ['name' => 'Manage Notifications', 'slug' => 'manage_notifications', 'description' => 'Can send and manage notifications'],
            ['name' => 'View Notifications', 'slug' => 'view_notifications', 'description' => 'Can view notifications'],
            ['name' => 'Send Notifications', 'slug' => 'send_notifications', 'description' => 'Can send notifications'],
            ['name' => 'View Notification Statistics', 'slug' => 'view_notification_stats', 'description' => 'Can view notification statistics'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }

        // Get admin and super-admin roles
        $adminRole = Role::where('slug', 'admin')->first();
        $superAdminRole = Role::where('slug', 'super-admin')->first();

        if ($adminRole) {
            $adminRole->permissions()->syncWithoutDetaching(
                Permission::whereIn('slug', [
                    'manage_notifications',
                    'view_notifications', 
                    'send_notifications',
                    'view_notification_stats'
                ])->pluck('id')
            );
        }

        if ($superAdminRole) {
            $superAdminRole->permissions()->sync(Permission::all()->pluck('id'));
        }
    }
}