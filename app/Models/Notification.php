<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'type',
        'created_by',
        'action_url',
        'action_text',
        'priority',
        'data',
        'is_public',
    ];

    protected $casts = [
        'data' => 'array',
        'priority' => 'integer',
        'is_public' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_notifications')
                    ->withPivot('id', 'read_at', 'dismissed_at', 'email_sent', 'email_sent_at', 'created_at', 'updated_at')
                    ->withTimestamps();
    }

    public function userNotifications()
    {
        return $this->hasMany(UserNotification::class);
    }

    public function getExcerptAttribute(): string
    {
        return Str::limit($this->message, 150);
    }

    public function getTypeColorAttribute(): string
    {
        $colors = [
            'announcement' => 'primary',
            'event' => 'info',
            'system' => 'secondary',
            'alert' => 'danger',
            'info' => 'info',
            'warning' => 'warning',
            'success' => 'success',
        ];

        return $colors[$this->type] ?? 'secondary';
    }

    public function getTypeIconAttribute(): string
    {
        $icons = [
            'announcement' => 'bullhorn',
            'event' => 'calendar-alt',
            'system' => 'cogs',
            'alert' => 'exclamation-triangle',
            'info' => 'info-circle',
            'warning' => 'exclamation-circle',
            'success' => 'check-circle',
        ];

        return $icons[$this->type] ?? 'bell';
    }

    public function getIsReadAttribute(): bool
    {
        if (auth()->check() && $this->relationLoaded('users')) {
            foreach ($this->users as $user) {
                if ($user->id === auth()->id() && $user->pivot->read_at) {
                    return true;
                }
            }
        }
        return false;
    }

    public function scopeForUser($query, $userId)
    {
        return $query->whereHas('users', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }

    public function scopeUnread($query, $userId = null)
    {
        $userId = $userId ?? auth()->id();
        
        return $query->whereHas('userNotifications', function ($q) use ($userId) {
            $q->where('user_id', $userId)
              ->whereNull('read_at');
        });
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}