<?php
// app/Models/Announcement.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Announcement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'type',
        'audience',
        'target_ids',
        'created_by',
        'is_published',
        'published_at',
        'expires_at',
        'views',
    ];

    protected $casts = [
        'target_ids' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getAudienceLabel()
    {
        $labels = [
            'all' => 'Everyone',
            'students' => 'Students Only',
            'faculty' => 'Faculty Only',
            'staff' => 'Staff Only',
            'event_managers' => 'Event Managers Only',
            'specific' => 'Specific Users',
        ];
        
        return $labels[$this->audience] ?? ucfirst($this->audience);
    }

    public function getTypeLabel()
    {
        $labels = [
            'event' => 'Event Announcement',
            'campus' => 'Campus Notice',
            'general' => 'General Announcement',
            'urgent' => 'Urgent Notice',
        ];
        
        return $labels[$this->type] ?? ucfirst($this->type);
    }

    public function getTypeColor()
    {
        $colors = [
            'event' => 'primary',
            'campus' => 'info',
            'general' => 'success',
            'urgent' => 'danger',
        ];
        
        return $colors[$this->type] ?? 'secondary';
    }

    public function getExcerpt($length = 150)
    {
        $content = strip_tags($this->content);
        return Str::limit($content, $length);
    }
    
    // Fixed: Add missing isActive method
    public function isActive()
    {
        if (!$this->is_published) {
            return false;
        }
        
        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }
        
        return true;
    }
    
    // Fixed: Add scope for active announcements
    public function scopeActive($query)
    {
        return $query->where('is_published', true)
                    ->where(function($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }
}
