<?php
// database/seeders/PermissionSeeder.php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // User Management
            ['name' => 'View Users', 'slug' => 'view_users', 'description' => 'Can view users list'],
            ['name' => 'Create Users', 'slug' => 'create_users', 'description' => 'Can create new users'],
            ['name' => 'Edit Users', 'slug' => 'edit_users', 'description' => 'Can edit existing users'],
            ['name' => 'Delete Users', 'slug' => 'delete_users', 'description' => 'Can delete users'],
            
            // Role Management
            ['name' => 'View Roles', 'slug' => 'view_roles', 'description' => 'Can view roles list'],
            ['name' => 'Create Roles', 'slug' => 'create_roles', 'description' => 'Can create new roles'],
            ['name' => 'Edit Roles', 'slug' => 'edit_roles', 'description' => 'Can edit existing roles'],
            ['name' => 'Delete Roles', 'slug' => 'delete_roles', 'description' => 'Can delete roles'],
            
            // Permission Management
            ['name' => 'View Permissions', 'slug' => 'view_permissions', 'description' => 'Can view permissions list'],
            ['name' => 'Create Permissions', 'slug' => 'create_permissions', 'description' => 'Can create new permissions'],
            ['name' => 'Edit Permissions', 'slug' => 'edit_permissions', 'description' => 'Can edit existing permissions'],
            ['name' => 'Delete Permissions', 'slug' => 'delete_permissions', 'description' => 'Can delete permissions'],
            
            // Dashboard
            ['name' => 'View Dashboard', 'slug' => 'view_dashboard', 'description' => 'Can view dashboard'],
            
            // Event Management
            ['name' => 'Manage Events', 'slug' => 'manage_events', 'description' => 'Can manage all events'],
            ['name' => 'View Events', 'slug' => 'view_events', 'description' => 'Can view events'],
            ['name' => 'Create Events', 'slug' => 'create_events', 'description' => 'Can create events'],
            ['name' => 'Edit Events', 'slug' => 'edit_events', 'description' => 'Can edit events'],
            ['name' => 'Delete Events', 'slug' => 'delete_events', 'description' => 'Can delete events'],
            
            // Speaker Management
            ['name' => 'Manage Speakers', 'slug' => 'manage_speakers', 'description' => 'Can manage all speakers'],
            ['name' => 'View Speakers', 'slug' => 'view_speakers', 'description' => 'Can view speakers'],
            ['name' => 'Create Speaker', 'slug' => 'create_speaker', 'description' => 'Can create speakers'],
            ['name' => 'Edit Speaker', 'slug' => 'edit_speaker', 'description' => 'Can edit speakers'],
            ['name' => 'Delete Speaker', 'slug' => 'delete_speaker', 'description' => 'Can delete speakers'],
            
            // Event Request Management
            ['name' => 'Manage Event Requests', 'slug' => 'manage_event_requests', 'description' => 'Can manage event requests'],
            ['name' => 'View Event Requests', 'slug' => 'view_event_requests', 'description' => 'Can view event requests'],
            ['name' => 'Create Event Request', 'slug' => 'create_event_request', 'description' => 'Can create event requests'],
            ['name' => 'Edit Event Request', 'slug' => 'edit_event_request', 'description' => 'Can edit event requests'],
            ['name' => 'Delete Event Request', 'slug' => 'delete_event_request', 'description' => 'Can delete event requests'],
            ['name' => 'Approve Event Requests', 'slug' => 'approve_event_requests', 'description' => 'Can approve event requests'],
            ['name' => 'Reject Event Requests', 'slug' => 'reject_event_requests', 'description' => 'Can reject event requests'],
            ['name' => 'Cancel Event Requests', 'slug' => 'cancel_event_requests', 'description' => 'Can cancel event requests'],
        
            // Notification permissions
            ['name' => 'Manage Notifications', 'slug' => 'manage_notifications', 'description' => 'Can send and manage notifications'],
            ['name' => 'View Notifications', 'slug' => 'view_notifications', 'description' => 'Can view notifications'],
            ['name' => 'Send Notifications', 'slug' => 'send_notifications', 'description' => 'Can send notifications'],
            ['name' => 'View Notification Statistics', 'slug' => 'view_notification_stats', 'description' => 'Can view notification statistics'],

            // Announcement permissions
            ['name' => 'Manage Announcements', 'slug' => 'manage_announcements', 'description' => 'Can create and manage announcements'],
            ['name' => 'View Announcements', 'slug' => 'view_announcements', 'description' => 'Can view announcements'],
            ['name' => 'Create Announcements', 'slug' => 'create_announcements', 'description' => 'Can create announcements'],
            ['name' => 'Edit Announcements', 'slug' => 'edit_announcements', 'description' => 'Can edit announcements'],
            ['name' => 'Delete Announcements', 'slug' => 'delete_announcements', 'description' => 'Can delete announcements'],
            ['name' => 'View Announcement Statistics', 'slug' => 'view_announcement_stats', 'description' => 'Can view announcement statistics'],

            // Feedback permissions
            ['name' => 'Manage Feedback', 'slug' => 'manage_feedback', 'description' => 'Can manage all feedback'],
            ['name' => 'View Feedback', 'slug' => 'view_feedback', 'description' => 'Can view feedback list'],
            ['name' => 'Create Feedback', 'slug' => 'create_feedback', 'description' => 'Can submit feedback'],
            ['name' => 'Update Feedback', 'slug' => 'update_feedback', 'description' => 'Can update feedback status'],
            ['name' => 'Delete Feedback', 'slug' => 'delete_feedback', 'description' => 'Can delete feedback'],
            ['name' => 'Respond to Feedback', 'slug' => 'respond_feedback', 'description' => 'Can respond to feedback'],
            ['name' => 'Export Feedback', 'slug' => 'export_feedback', 'description' => 'Can export feedback data'],
            ['name' => 'View Feedback Analytics', 'slug' => 'view_feedback_analytics', 'description' => 'Can view feedback analytics'],
        
            // Event Registration permissions - REMOVED THE 'group' FIELD
            ['name' => 'Manage Event Registrations', 'slug' => 'manage_event_registrations', 'description' => 'Can manage all event registrations'],
            ['name' => 'View Registrations', 'slug' => 'view_registrations', 'description' => 'Can view registrations'],
            ['name' => 'Confirm Registrations', 'slug' => 'confirm_registrations', 'description' => 'Can confirm registrations'],
            ['name' => 'Cancel Registrations', 'slug' => 'cancel_registrations', 'description' => 'Can cancel registrations'],
            ['name' => 'Export Registrations', 'slug' => 'export_registrations', 'description' => 'Can export registrations'],
            
            // Campus Management
            ['name' => 'Manage Campuses', 'slug' => 'manage_campuses', 'description' => 'Can manage campuses'],
            ['name' => 'View Campuses', 'slug' => 'view_campuses', 'description' => 'Can view campuses'],
            
            // Building Management
            ['name' => 'Manage Buildings', 'slug' => 'manage_buildings', 'description' => 'Can manage buildings'],
            ['name' => 'View Buildings', 'slug' => 'view_buildings', 'description' => 'Can view buildings'],
            
            // Venue Management
            ['name' => 'Manage Venues', 'slug' => 'manage_venues', 'description' => 'Can manage venues'],
            ['name' => 'View Venues', 'slug' => 'view_venues', 'description' => 'Can view venues'],
            ['name' => 'Book Venues', 'slug' => 'book_venues', 'description' => 'Can book venues'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                [
                    'name' => $permission['name'],
                    'description' => $permission['description'] ?? null,
                ]
            );
        }

        // Assign all permissions to Super Admin
        $superAdmin = Role::where('slug', 'super-admin')->first();
        if ($superAdmin) {
            $allPermissions = Permission::all();
            $superAdmin->permissions()->sync($allPermissions->pluck('id'));
        }

        // Assign permissions to Admin
        $admin = Role::where('slug', 'admin')->first();
        if ($admin) {
            $adminPermissions = Permission::whereIn('slug', [
                'view_users', 'create_users', 'edit_users', 'delete_users',
                'view_roles', 'create_roles', 'edit_roles',
                'view_permissions', 'view_dashboard',
                'manage_events', 'view_events', 'create_events', 'edit_events', 'delete_events',
                'manage_speakers', 'view_speakers', 'create_speaker', 'edit_speaker', 'delete_speaker',
                'manage_event_requests', 'view_event_requests', 'create_event_request', 
                'edit_event_request', 'delete_event_request', 'approve_event_requests', 'reject_event_requests',
                'manage_notifications', 'view_notifications', 'send_notifications', 'view_notification_stats',
                'manage_announcements', 'view_announcements', 'create_announcements', 
                'edit_announcements', 'delete_announcements', 'view_announcement_stats',
                'manage_feedback', 'view_feedback', 'update_feedback', 'respond_feedback', 
                'export_feedback', 'view_feedback_analytics',
                'manage_event_registrations', 'view_registrations', 'confirm_registrations', 
                'cancel_registrations', 'export_registrations',
                'manage_campuses', 'view_campuses',
                'manage_buildings', 'view_buildings',
                'manage_venues', 'view_venues', 'book_venues',
            ])->get();
            $admin->permissions()->sync($adminPermissions->pluck('id'));
        }

        // Assign permissions to Event Manager
        $eventManager = Role::where('slug', 'event-manager')->first();
        if ($eventManager) {
            $eventManagerPermissions = Permission::whereIn('slug', [
                'view_dashboard',
                'manage_events', 'view_events', 'create_events', 'edit_events',
                'manage_speakers', 'view_speakers', 'create_speaker', 'edit_speaker',
                'manage_event_requests', 'view_event_requests', 'create_event_request', 
                'edit_event_request', 'approve_event_requests', 'reject_event_requests',
                'manage_notifications', 'view_notifications', 'send_notifications',
                'view_announcements',
                'view_feedback', 'create_feedback', 'update_feedback', 'respond_feedback',
                'view_registrations', 'confirm_registrations', 'cancel_registrations',
                'view_campuses', 'view_buildings', 'view_venues', 'book_venues',
            ])->get();
            $eventManager->permissions()->sync($eventManagerPermissions->pluck('id'));
        }

        // Assign permissions to Faculty
        $faculty = Role::where('slug', 'faculty')->first();
        if ($faculty) {
            $facultyPermissions = Permission::whereIn('slug', [
                'view_dashboard', 'view_events',
                'create_event_request', 'view_event_requests', 'edit_event_request',
                'view_notifications',
                'view_announcements',
                'create_feedback', 'view_feedback', 'update_feedback', 'respond_feedback',
                'view_registrations',
                'view_campuses', 'view_buildings', 'view_venues', 'book_venues',
            ])->get();
            $faculty->permissions()->sync($facultyPermissions->pluck('id'));
        }

        // Assign permissions to Student
        $student = Role::where('slug', 'student')->first();
        if ($student) {
            $studentPermissions = Permission::whereIn('slug', [
                'view_dashboard', 'view_events',
                'create_event_request', 'view_event_requests',
                'view_notifications',
                'view_announcements',
                'create_feedback',
                'view_registrations',
                'view_campuses', 'view_buildings', 'view_venues',
            ])->get();
            $student->permissions()->sync($studentPermissions->pluck('id'));
        }

        // Assign permissions to Guest
        $guest = Role::where('slug', 'guest')->first();
        if ($guest) {
            $guestPermissions = Permission::whereIn('slug', [
                'view_events',
                'create_feedback',
            ])->get();
            $guest->permissions()->sync($guestPermissions->pluck('id'));
        }
        
        $this->command->info('Permissions seeded successfully!');
    }
}
