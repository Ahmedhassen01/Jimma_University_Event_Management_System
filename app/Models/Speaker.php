<?php
// app/Models/Speaker.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class Speaker extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'title',
        'position',
        'organization',
        'email',
        'phone',
        'bio',
        'photo',
        'website',
        'twitter',
        'linkedin',
        'facebook',
        'is_featured',
        'is_active',
        'expertise',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'expertise' => 'array',
    ];

    protected $appends = [
        'photo_url',
        'full_name',
        'display_name',
        'events_count',
        'upcoming_events_count',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($speaker) {
            if (empty($speaker->slug)) {
                $speaker->slug = \Str::slug($speaker->name);
                $counter = 1;
                while (static::where('slug', $speaker->slug)->exists()) {
                    $speaker->slug = \Str::slug($speaker->name) . '-' . $counter;
                    $counter++;
                }
            }
        });

        static::deleting(function ($speaker) {
            // Delete photo when speaker is deleted
            if ($speaker->photo) {
                Storage::disk('public')->delete($speaker->photo);
            }
        });
    }

    // Relationship with events
    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_speaker')
                    ->withPivot('session_title', 'session_time', 'session_duration', 
                               'session_description', 'order', 'is_keynote', 
                               'is_moderator', 'is_panelist', 'custom_data')
                    ->withTimestamps()
                    ->orderBy('order');
    }

    /**
     * Get upcoming events.
     */
    public function upcomingEvents()
    {
        return $this->events()->where('start_date', '>', now());
    }

    /**
     * Get past events.
     */
    public function pastEvents()
    {
        return $this->events()->where('end_date', '<', now());
    }

    /**
     * Get current events.
     */
    public function currentEvents()
    {
        return $this->events()
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }

    /**
     * Get keynote events.
     */
    public function keynoteEvents()
    {
        return $this->belongsToMany(Event::class, 'event_speaker')
                    ->wherePivot('is_keynote', true)
                    ->withTimestamps();
    }

    /**
     * Get events count attribute.
     */
    public function getEventsCountAttribute()
    {
        return $this->events()->count();
    }

    /**
     * Get upcoming events count attribute.
     */
    public function getUpcomingEventsCountAttribute()
    {
        return $this->upcomingEvents()->count();
    }

    /**
     * Accessor for photo URL.
     */
    protected function getPhotoUrlAttribute()
    {
        if (!$this->photo) {
            return asset('images/default-speaker.jpg');
        }
        
        if (filter_var($this->photo, FILTER_VALIDATE_URL)) {
            return $this->photo;
        }
        
        if (Storage::disk('public')->exists($this->photo)) {
            return Storage::url($this->photo);
        }
        
        return asset('images/default-speaker.jpg');
    }

    /**
     * Accessor for full name with title.
     */
    protected function getFullNameAttribute()
    {
        $name = $this->name;
        if ($this->title) {
            $name = $this->title . ' ' . $name;
        }
        return $name;
    }

    /**
     * Accessor for display name.
     */
    protected function getDisplayNameAttribute()
    {
        $display = $this->full_name;
        if ($this->position || $this->organization) {
            $display .= ' - ' . ($this->position ?: $this->organization);
        }
        return $display;
    }

    /**
     * Accessor for expertise list.
     */
    protected function getExpertiseListAttribute()
    {
        if (empty($this->expertise) || !is_array($this->expertise)) {
            return [];
        }
        
        return $this->expertise;
    }

    /**
     * Check if speaker has photo.
     */
    public function hasPhoto()
    {
        return !empty($this->photo);
    }

    /**
     * Upload photo.
     */
    public function uploadPhoto($file)
    {
        // Delete old photo if exists
        if ($this->photo) {
            Storage::disk('public')->delete($this->photo);
        }
        
        // Generate unique filename
        $filename = 'speaker-' . $this->id . '-' . time() . '.' . $file->getClientOriginalExtension();
        
        // Store the file
        $path = $file->storeAs('speakers', $filename, 'public');
        
        // Update the speaker with new photo path
        $this->photo = $path;
        $this->save();
        
        return $path;
    }

    /**
     * Delete photo.
     */
    public function deletePhoto()
    {
        if ($this->photo && Storage::disk('public')->exists($this->photo)) {
            Storage::disk('public')->delete($this->photo);
            $this->photo = null;
            $this->save();
        }
    }

    // Scope for active speakers
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for featured speakers
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->where('is_active', true);
    }

    // Scope for search
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('position', 'like', "%{$search}%")
              ->orWhere('organization', 'like', "%{$search}%")
              ->orWhere('bio', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    // Get social media links
    public function getSocialLinksAttribute()
    {
        $links = [];
        
        if ($this->twitter) {
            $links['twitter'] = 'https://twitter.com/' . ltrim($this->twitter, '@');
        }
        
        if ($this->linkedin) {
            $links['linkedin'] = $this->linkedin;
        }
        
        if ($this->facebook) {
            $links['facebook'] = $this->facebook;
        }
        
        if ($this->website) {
            $links['website'] = $this->website;
        }
        
        return $links;
    }

    /**
     * Get the expertise as a comma-separated string.
     */
    public function getExpertiseStringAttribute()
    {
        if (empty($this->expertise) || !is_array($this->expertise)) {
            return '';
        }
        
        return implode(', ', $this->expertise);
    }

    /**
     * Set the expertise from a comma-separated string.
     */
    public function setExpertiseStringAttribute($value)
    {
        if (empty($value)) {
            $this->expertise = null;
        } else {
            $this->expertise = array_map('trim', explode(',', $value));
        }
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured()
    {
        $this->is_featured = !$this->is_featured;
        $this->save();
    }

    /**
     * Toggle active status.
     */
    public function toggleActive()
    {
        $this->is_active = !$this->is_active;
        $this->save();
    }
}