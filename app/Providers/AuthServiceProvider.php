<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // Your policies
    ];

    public function boot(): void
    {
        // Define gates for permissions
        Gate::define('access_admin_dashboard', function (User $user) {
            // Super admin bypass
            if ($user->hasRole('super-admin') || $user->hasRole('admin')) {
                return true;
            }
            
            // Check if user has any admin-related permission
            $adminPermissions = [
                'view_dashboard',
                'manage_events',
                'view_event_requests',
                'manage_users',
                'manage_feedback',
                'manage_announcements',
                'manage_venues',
                'manage_speakers',
                'manage_roles',
                'manage_permissions'
            ];
            
            foreach ($adminPermissions as $permission) {
                if ($user->hasPermission($permission)) {
                    return true;
                }
            }
            
            return false;
        });

        // Define dynamic gates for all permissions
        $permissions = [
            'manage_users', 'manage_roles', 'manage_permissions',
            'manage_events', 'manage_speakers', 'manage_venues',
            'manage_event_requests', 'approve_event_requests', 'reject_event_requests',
            'manage_feedback', 'manage_announcements', 'manage_notifications',
            'send_notifications', 'view_notification_stats', 'view_announcement_stats',
            'view_event_participants', 'export_event_data', 'manage_event_checkin',
            'manage_registrations', 'create_announcements', 'edit_announcements',
            'delete_announcements'
        ];

        foreach ($permissions as $permission) {
            Gate::define($permission, function (User $user) use ($permission) {
                // Super admin bypass
                if ($user->hasRole('super-admin')) {
                    return true;
                }
                
                return $user->hasPermission($permission);
            });
        }
    }
}