<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_active',
        'profile_photo',
        'phone',
        'date_of_birth',
        'office_address',
        'expertise',
        'department',
        'faculty',
        'student_id',
        'employee_id',
        'year_of_study',
        'position',
        'email_notifications',
        'newsletter_subscription',
        'event_reminders',
        'theme_preference',
        'language',
        'status',
        'last_login_at',
        'approved_at',
        'approved_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'date_of_birth' => 'date',
        'email_notifications' => 'boolean',
        'newsletter_subscription' => 'boolean',
        'event_reminders' => 'boolean',
        'last_login_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    protected $appends = [
        'unread_notifications_count',
        'age',
        'user_type_label',
        'status_badge_class'
    ];

    /**
     * Get the user's age
     */
    public function getAgeAttribute()
    {
        if ($this->date_of_birth) {
            return $this->date_of_birth->age;
        }
        return null;
    }

    /**
     * Get user type label attribute
     */
    public function getUserTypeLabelAttribute()
    {
        $types = [
            'student' => 'Student',
            'employee' => 'Employee',
            'faculty' => 'Faculty Member',
            'staff' => 'University Staff',
            'alumni' => 'Alumni',
            'guest' => 'Guest',
            'other' => 'Other'
        ];
        
        return $types[$this->expertise] ?? 'Not Specified';
    }

    /**
     * Get status badge class attribute
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'active' => 'success',
            'pending' => 'warning',
            'suspended' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Get normalized profile photo URL.
     */
    public function getProfilePhotoUrlAttribute()
    {
        if (empty($this->profile_photo)) {
            return null;
        }

        $path = str_replace('\\', '/', (string) $this->profile_photo);
        $path = ltrim($path, '/');

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }
        
        return route('profile.photo.show', ['user' => $this->id], false);
    }

    /**
     * Get the role that owns the user
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the user who approved this account
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the registrations for the user
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }
    
    /**
     * Get the event requests for the user
     */
    public function eventRequests(): HasMany
    {
        return $this->hasMany(EventRequest::class);
    }
    
    /**
     * Get the waitlists for the user
     */
    public function waitlists(): HasMany
    {
        return $this->hasMany(Waitlist::class);
    }

    /**
     * Get the announcement views for the user
     */
    public function announcementViews(): HasMany
    {
        return $this->hasMany(AnnouncementView::class);
    }

    /**
     * Get the viewed announcements for the user
     */
    public function viewedAnnouncements()
    {
        return $this->belongsToMany(Announcement::class, 'announcement_views')
                    ->withTimestamps();
    }

    /**
     * Get the user notifications
     */
    public function userNotifications()
    {
        return $this->hasMany(UserNotification::class);
    }

    /**
     * Get the notifications for the user
     */
    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'user_notifications')
                    ->withPivot('id', 'read_at', 'dismissed_at', 'email_sent', 'email_sent_at', 'created_at', 'updated_at')
                    ->withTimestamps()
                    ->orderBy('user_notifications.created_at', 'desc');
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadNotificationsCountAttribute()
    {
        // First check if dismissed_at column exists
        if (!\Schema::hasColumn('user_notifications', 'dismissed_at')) {
            return $this->userNotifications()
                ->whereNull('read_at')
                ->count();
        }
        
        return $this->userNotifications()
            ->whereNull('read_at')
            ->whereNull('dismissed_at')
            ->count();
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        if (!$this->role) {
            return false;
        }
        
        return in_array($this->role->slug, ['super-admin', 'admin']);
    }

    /**
     * Check if user has permission
     */
    
    public function hasPermission($permissionSlug)
{
    if (!$this->role) {
        \Log::error("User has no role", [
            'user_id' => $this->id, 
            'user_email' => $this->email,
            'user_name' => $this->name,
            'permission' => $permissionSlug
        ]);
        return false;
    }
    
    // Log role information
    \Log::info("User role check", [
        'user_id' => $this->id,
        'user_email' => $this->email,
        'role_id' => $this->role->id,
        'role_name' => $this->role->name,
        'role_slug' => $this->role->slug,
        'permission_requested' => $permissionSlug
    ]);
    
    // Super admin has all permissions
    if ($this->role->slug === 'super-admin') {
        \Log::info("User is super-admin, granting all permissions");
        return true;
    }
    
    // Check specific permission
    $hasPermission = $this->role->permissions()
        ->where('slug', $permissionSlug)
        ->exists();
    
    \Log::info("Permission check result", [
        'user_id' => $this->id,
        'user_email' => $this->email,
        'role' => $this->role->slug,
        'permission' => $permissionSlug,
        'has_permission' => $hasPermission,
        'permission_id' => $this->role->permissions()->where('slug', $permissionSlug)->first()?->id
    ]);
    
    return $hasPermission;
}

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission($permissions)
    {
        if (!is_array($permissions)) {
            $permissions = [$permissions];
        }
        
        if (!$this->role) {
            return false;
        }
        
        if ($this->role->slug === 'super-admin') {
            return true;
        }
        
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Check if user has role
     */
    public function hasRole($roleSlug)
    {
        if (!$this->role) {
            return false;
        }
        
        return $this->role->slug === $roleSlug;
    }

    /**
     * Check if user is registered for an event
     */
    public function isRegisteredForEvent($eventId)
    {
        return $this->registrations()
            ->where('event_id', $eventId)
            ->whereIn('status', ['confirmed', 'pending'])
            ->exists();
    }

    /**
     * Get confirmed registrations count
     */
    public function getConfirmedRegistrationsCountAttribute()
    {
        return $this->registrations()->where('status', 'confirmed')->count();
    }

    /**
     * Get active waitlists count
     */
    public function getActiveWaitlistsCountAttribute()
    {
        return $this->waitlists()->whereNull('converted_at')->count();
    }

    /**
     * Check if user has permission to (alias for hasPermission)
     */
    public function hasPermissionTo($permission)
    {
        return $this->hasPermission($permission);
    }

    /**
     * Check if user can (override Laravel's can method)
     */
    public function can($ability, $arguments = [])
    {
        return $this->hasPermission($ability);
    }
    
    /**
     * Send notification to user
     */
    public function sendNotification($notificationData, $markAsUnread = true)
    {
        $notification = Notification::create([
            'title' => $notificationData['title'],
            'message' => $notificationData['message'],
            'type' => $notificationData['type'] ?? 'info',
            'data' => $notificationData['data'] ?? [],
            'created_by' => auth()->id() ?? null,
            'action_url' => $notificationData['action_url'] ?? null,
            'action_text' => $notificationData['action_text'] ?? null,
            'priority' => $notificationData['priority'] ?? 0,
            'is_public' => $notificationData['is_public'] ?? false,
        ]);
        
        $this->notifications()->attach($notification->id, [
            'read_at' => $markAsUnread ? null : now(),
        ]);
        
        if ($notificationData['send_email'] ?? false) {
            $this->sendEmailNotification($notification);
        }
        
        return $notification;
    }
    
    /**
     * Send email notification
     */
    private function sendEmailNotification($notification)
    {
        Log::info('Email notification would be sent', [
            'user' => $this->email,
            'notification' => $notification->title
        ]);
        // Implement actual email sending here
    }

    /**
     * Scope for active users
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function ($activeQuery) {
                $activeQuery->where('is_active', true)
                    ->orWhereNull('is_active');
            });
    }

    /**
     * Scope for pending users
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for users by expertise
     */
    public function scopeByExpertise($query, $expertise)
    {
        return $query->where('expertise', $expertise);
    }

    /**
     * Check if account is active
     */
    public function isActive()
    {
        return $this->isAccountActive();
    }

    /**
     * Check if account can access the system.
     */
    public function isAccountActive(): bool
    {
        return $this->status === 'active' && $this->is_active !== false;
    }

    /**
     * Check if account is pending approval
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }
}
