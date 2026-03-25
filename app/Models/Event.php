<?php
// app/Models/Event.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'title',
        'slug',
        'description',
       // 'short_description',
        'start_date',
        'end_date',
        'campus_id',
        'building_id',
        'venue_id',
        'campus',
        'building',
        'venue',
        'event_type',
        'organizer',
        'speaker_names',
        'speaker_bios',
        'speaker_photos',
        'speaker_titles',
        'speaker_organizations',
        'contact_email',
        'contact_phone',
        'max_attendees',
        'registered_attendees',
        'is_featured',
        'is_public',
        'is_cancelled',
        'cancellation_reason',
        'cancelled_at',
        'cancelled_by',
        'requires_registration',
        'registration_link',
        'tags',
        'image',
        'status',
        'additional_venue_info',
        'views_count',
        'gallery_images',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_featured' => 'boolean',
        'is_public' => 'boolean',
        'is_cancelled' => 'boolean',
        'cancelled_at' => 'datetime',
        'requires_registration' => 'boolean',
        'tags' => 'array',
        'gallery_images' => 'array',
        'additional_venue_info' => 'array',
        'max_attendees' => 'integer',
        'registered_attendees' => 'integer',
        'views_count' => 'integer',
        'speaker_names' => 'array',
        'speaker_bios' => 'array',
        'speaker_photos' => 'array',
        'speaker_titles' => 'array',
        'speaker_organizations' => 'array',
    ];

    protected $appends = [
        'image_url',
        'formatted_date_range',
        'is_full',
        'available_seats',
        'attendance_percentage',
        'remaining_seats',
        'status_display',
        'cancellation_status',
        'venue_name',
        'building_name',
        'campus_name',
        'speakers_list',
        'speakers_count',
        'has_speakers',
        'event_type_icon',
        'event_type_color',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->title);
                $counter = 1;
                while (static::where('slug', $event->slug)->exists()) {
                    $event->slug = Str::slug($event->title) . '-' . $counter;
                    $counter++;
                }
            }
        });

        static::deleting(function ($event) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
        });

        static::retrieved(function ($event) {
            // Log image path for debugging (remove in production)
            if ($event->image) {
                Log::info('Event ' . $event->id . ' image path: ' . $event->image);
            }
        });
    }

    /**
     * Relationships
     */
    public function speakers()
    {
        return $this->belongsToMany(Speaker::class, 'event_speaker')
                    ->withPivot('session_title', 'session_time', 'session_duration', 
                               'session_description', 'order', 'is_keynote', 
                               'is_moderator', 'is_panelist', 'custom_data')
                    ->withTimestamps()
                    ->orderBy('order');
    }

    public function keynoteSpeakers()
    {
        return $this->belongsToMany(Speaker::class, 'event_speaker')
                    ->withPivot('session_title', 'session_time', 'session_duration', 
                               'session_description', 'order')
                    ->wherePivot('is_keynote', true)
                    ->orderBy('order');
    }

    public function moderators()
    {
        return $this->belongsToMany(Speaker::class, 'event_speaker')
                    ->withPivot('session_title', 'session_time', 'session_duration', 
                               'session_description', 'order')
                    ->wherePivot('is_moderator', true)
                    ->orderBy('order');
    }

    public function panelists()
    {
        return $this->belongsToMany(Speaker::class, 'event_speaker')
                    ->withPivot('session_title', 'session_time', 'session_duration', 
                               'session_description', 'order')
                    ->wherePivot('is_panelist', true)
                    ->orderBy('order');
    }

    public function campusRelation()
    {
        return $this->belongsTo(Campus::class, 'campus_id');
    }

    public function buildingRelation()
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

    public function venueRelation()
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function confirmedRegistrations()
    {
        return $this->hasMany(EventRegistration::class)->where('status', 'confirmed');
    }

    public function waitlists()
    {
        return $this->hasMany(Waitlist::class);
    }

    public function cancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    /**
     * Cancellation Methods
     */
    public function cancel($reason = null)
    {
        $this->is_cancelled = true;
        $this->cancellation_reason = $reason;
        $this->cancelled_at = now();
        $this->cancelled_by = auth()->id();
        $this->save();

        return $this;
    }

    public function uncancel()
    {
        $this->is_cancelled = false;
        $this->cancellation_reason = null;
        $this->cancelled_at = null;
        $this->cancelled_by = null;
        $this->save();
        
        return $this;
    }

    public function isCancelled()
    {
        return $this->is_cancelled;
    }

    protected function createCancellationAnnouncement($reason)
    {
        $title = "CANCELLED: {$this->title}";
        $content = "<div class='cancellation-notice'>";
        $content .= "<h2><i class='fas fa-exclamation-triangle text-danger'></i> Event Cancelled</h2>";
        $content .= "<p>We regret to inform you that the following event has been cancelled:</p>";
        $content .= "<h3>{$this->title}</h3>";
        $content .= "<p><strong>Originally scheduled for:</strong> {$this->start_date->format('l, F j, Y \a\t g:i A')}</p>";
        $content .= "<p><strong>Location:</strong> {$this->venue_name}, {$this->campus_name}</p>";
        
        if ($reason) {
            $content .= "<div class='alert alert-warning'><strong>Reason for cancellation:</strong><br>{$reason}</div>";
        }
        
        $content .= "<p>We apologize for any inconvenience this may cause. Please contact the organizer for more information.</p>";
        $content .= "<p><strong>Organizer:</strong> {$this->organizer}</p>";
        
        if ($this->contact_email) {
            $content .= "<p><strong>Contact:</strong> <a href='mailto:{$this->contact_email}'>{$this->contact_email}</a></p>";
        }
        
        $content .= "</div>";
        
        $announcement = new Announcement();
        $announcement->title = $title;
        $announcement->content = $content;
        $announcement->type = 'urgent';
        $announcement->audience = 'all';
        $announcement->created_by = auth()->id();
        $announcement->is_published = true;
        $announcement->published_at = now();
        $announcement->save();
        
        $this->notifyAttendeesOfCancellation($announcement, $reason);
        
        return $announcement;
    }

    protected function notifyAttendeesOfCancellation($announcement, $reason)
    {
        $registeredUsers = $this->registrations()
            ->whereIn('status', ['confirmed', 'pending'])
            ->with('user')
            ->get()
            ->pluck('user');
        
        if ($registeredUsers->isEmpty()) {
            return;
        }
        
        foreach ($registeredUsers as $user) {
            $notification = new Notification();
            $notification->title = "⚠️ EVENT CANCELLED: {$this->title}";
            $notification->message = "The event you registered for has been cancelled." . ($reason ? " Reason: {$reason}" : "");
            $notification->type = 'alert';
            $notification->priority = 2;
            $notification->action_url = route('announcements.show', $announcement->id);
            $notification->action_text = 'View Cancellation Details';
            $notification->data = [
                'event_id' => $this->id,
                'event_title' => $this->title,
                'cancellation_reason' => $reason,
                'announcement_id' => $announcement->id
            ];
            $notification->created_by = auth()->id();
            $notification->save();
            
            $notification->users()->attach($user->id, [
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    /**
     * Scope Queries
     */
    public function scopeActive($query)
    {
        return $query->where('is_cancelled', false)
                    ->where('is_published', true)
                    ->where(function($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }

    public function scopeUpcoming($query)
    {
        return $query->where('is_cancelled', false)
                    ->where('start_date', '>', now());
    }

    public function scopeOngoing($query)
    {
        return $query->where('is_cancelled', false)
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }

    public function scopePast($query)
    {
        return $query->where('is_cancelled', false)
                    ->where('end_date', '<', now());
    }

    public function scopeNotCancelled($query)
    {
        return $query->where('is_cancelled', false);
    }

    public function scopeCancelled($query)
    {
        return $query->where('is_cancelled', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('short_description', 'like', "%{$search}%")
              ->orWhere('organizer', 'like', "%{$search}%");
        });
    }

    /**
     * Check Methods
     */
    public function isRegisteredByUser($userId = null)
    {
        $userId = $userId ?? Auth::id();
        
        if (!$userId) {
            return false;
        }
        
        return $this->registrations()
            ->where('user_id', $userId)
            ->whereIn('status', ['confirmed', 'pending'])
            ->exists();
    }

    public function isOnWaitlistByUser($userId = null)
    {
        $userId = $userId ?? Auth::id();
        
        if (!$userId) {
            return false;
        }
        
        return $this->waitlists()
            ->where('user_id', $userId)
            ->whereNull('converted_at')
            ->exists();
    }

    public function getUserWaitlistPosition($userId = null)
    {
        $userId = $userId ?? Auth::id();
        
        if (!$userId) {
            return null;
        }
        
        $waitlist = $this->waitlists()
            ->where('user_id', $userId)
            ->whereNull('converted_at')
            ->first();
            
        return $waitlist ? $waitlist->position : null;
    }

    public function addToWaitlist($userId = null)
    {
        $userId = $userId ?? Auth::id();
        
        if (!$userId) {
            return null;
        }
        
        if ($this->isOnWaitlistByUser($userId)) {
            return null;
        }
        
        $nextPosition = $this->waitlists()->whereNull('converted_at')->max('position') ?? 0;
        $nextPosition++;
        
        return $this->waitlists()->create([
            'user_id' => $userId,
            'position' => $nextPosition,
            'joined_at' => now(),
        ]);
    }

    public function fillFromWaitlist($numberOfSpots = 1)
    {
        $movedUsers = [];
        
        $waitlistEntries = $this->waitlists()
            ->with('user')
            ->whereNull('converted_at')
            ->orderBy('position')
            ->limit($numberOfSpots)
            ->get();
        
        foreach ($waitlistEntries as $waitlistEntry) {
            $registration = EventRegistration::create([
                'event_id' => $this->id,
                'user_id' => $waitlistEntry->user_id,
                'guest_count' => 1,
                'status' => 'confirmed',
                'confirmed_at' => now(),
            ]);
            
            $this->increment('registered_attendees', 1);
            
            $waitlistEntry->update([
                'converted_at' => now(),
            ]);
            
            $movedUsers[] = $waitlistEntry->user;
        }
        
        return $movedUsers;
    }

    /**
     * Toggle Methods
     */
    public function toggleFeatured()
    {
        $this->is_featured = !$this->is_featured;
        $this->save();
    }

    public function togglePublic()
    {
        $this->is_public = !$this->is_public;
        $this->save();
    }

    public function isRegistrationOpen()
    {
        if ($this->is_cancelled) {
            return false;
        }
        
        if (!$this->requires_registration) {
            return false;
        }
        
        if ($this->max_attendees && $this->registered_attendees >= $this->max_attendees) {
            return false;
        }
        
        return $this->start_date > now();
    }

    /**
     * Attribute Getters
     */
    public function getSpeakersListAttribute()
    {
        if ($this->speakers->isNotEmpty()) {
            return $this->speakers;
        }
        
        $speakers = [];
        if (!empty($this->speaker_names)) {
            foreach ($this->speaker_names as $index => $name) {
                $speakers[] = (object)[
                    'name' => $name,
                    'title' => $this->speaker_titles[$index] ?? null,
                    'bio' => $this->speaker_bios[$index] ?? null,
                    'photo' => $this->speaker_photos[$index] ?? null,
                    'organization' => $this->speaker_organizations[$index] ?? null,
                ];
            }
        }
        
        return collect($speakers);
    }

    public function getSpeakersCountAttribute()
    {
        return $this->speakers()->count();
    }

    public function getHasSpeakersAttribute()
    {
        return $this->speakers_count > 0;
    }

    public function getIsFullAttribute()
    {
        if (!$this->max_attendees) {
            return false;
        }
        
        return $this->registered_attendees >= $this->max_attendees;
    }

    public function getAvailableSeatsAttribute()
    {
        if (!$this->max_attendees) {
            return null;
        }
        
        return max(0, $this->max_attendees - $this->registered_attendees);
    }

    public function getStatusDisplayAttribute()
    {
        if ($this->is_cancelled) {
            return 'cancelled';
        }
        
        $now = now();
        
        if ($now < $this->start_date) {
            return 'upcoming';
        } elseif ($now >= $this->start_date && $now <= $this->end_date) {
            return 'ongoing';
        } else {
            return 'completed';
        }
    }

    public function getCancellationStatusAttribute()
    {
        return $this->is_cancelled ? 'cancelled' : $this->status_display;
    }

    public function getStatusBadgeAttribute()
    {
        if ($this->is_cancelled) {
            return ['class' => 'bg-danger', 'text' => 'Cancelled'];
        }
        
        $status = $this->status_display;
        $badges = [
            'upcoming' => ['class' => 'bg-primary', 'text' => 'Upcoming'],
            'ongoing' => ['class' => 'bg-success', 'text' => 'Ongoing'],
            'completed' => ['class' => 'bg-secondary', 'text' => 'Completed']
        ];
        
        return $badges[$status] ?? ['class' => 'bg-secondary', 'text' => 'Unknown'];
    }

    public function getFormattedDateRangeAttribute()
    {
        if ($this->start_date->format('Y-m-d') === $this->end_date->format('Y-m-d')) {
            return $this->start_date->format('M d, Y');
        }
        
        return $this->start_date->format('M d') . ' - ' . $this->end_date->format('M d, Y');
    }

    public function getVenueNameAttribute()
    {
        if ($this->venueRelation && $this->venueRelation->exists) {
            return $this->venueRelation->name;
        }
        
        if (!empty($this->venue) && is_string($this->venue)) {
            return $this->venue;
        }
        
        return 'Not specified';
    }

    public function getBuildingNameAttribute()
    {
        if ($this->buildingRelation && $this->buildingRelation->exists) {
            return $this->buildingRelation->name;
        }
        
        if (!empty($this->building) && is_string($this->building)) {
            return $this->building;
        }
        
        return 'Not specified';
    }

    public function getCampusNameAttribute()
    {
        if ($this->campusRelation && $this->campusRelation->exists) {
            return $this->campusRelation->name;
        }
        
        if (!empty($this->campus) && is_string($this->campus)) {
            return $this->campus;
        }
        
        return 'Not specified';
    }

    public function getVenueDetailsAttribute()
    {
        if ($this->venueRelation && $this->venueRelation->exists) {
            return $this->venueRelation;
        }
        
        return $this->venue;
    }

    public function getBuildingDetailsAttribute()
    {
        if ($this->buildingRelation && $this->buildingRelation->exists) {
            return $this->buildingRelation;
        }
        
        return $this->building;
    }

    public function getCampusDetailsAttribute()
    {
        if ($this->campusRelation && $this->campusRelation->exists) {
            return $this->campusRelation;
        }
        
        return $this->campus;
    }

    public function getRemainingSeatsAttribute()
    {
        if (!$this->max_attendees) {
            return null;
        }
        
        return max(0, $this->max_attendees - $this->registered_attendees);
    }

    public function getAttendancePercentageAttribute()
    {
        if (!$this->max_attendees) {
            return 0;
        }
        
        return ($this->registered_attendees / $this->max_attendees) * 100;
    }

    public function getFormattedTagsAttribute()
    {
        if (empty($this->tags) || !is_array($this->tags)) {
            return [];
        }
        
        return array_map('trim', $this->tags);
    }

    /**
     * FIXED: Comprehensive image URL handling
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('images/default-event.jpg');
        }

        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        // Serve local images via controller route to avoid storage symlink issues.
        return route('events.image', ['event' => $this->id]);
    }

    public function getImagePathAttribute()
    {
        if (!$this->image) {
            return null;
        }
        
        if (Storage::disk('public')->exists($this->image)) {
            return Storage::path('public/' . $this->image);
        }
        
        return null;
    }

    public function getEventTypeIconAttribute()
    {
        $icons = [
            'academic' => 'graduation-cap',
            'cultural' => 'music',
            'sports' => 'futbol',
            'conference' => 'microphone',
            'workshop' => 'tools',
            'seminar' => 'chalkboard-teacher',
            'exhibition' => 'images',
            'outreach' => 'heart',
        ];
        
        return $icons[$this->event_type] ?? 'calendar-day';
    }

    public function getEventTypeColorAttribute()
    {
        $colors = [
            'academic' => 'primary',
            'cultural' => 'success',
            'sports' => 'danger',
            'conference' => 'info',
            'workshop' => 'warning',
            'seminar' => 'dark',
            'exhibition' => 'secondary',
            'outreach' => 'success',
        ];
        
        return $colors[$this->event_type] ?? 'secondary';
    }

    /**
     * Image Methods
     */
    public function deleteImage()
    {
        if ($this->image) {
            // Try to delete from storage
            if (Storage::disk('public')->exists($this->image)) {
                Storage::disk('public')->delete($this->image);
            }
            
            // Also try to delete from public path
            $publicPath = public_path('storage/' . $this->image);
            if (file_exists($publicPath)) {
                @unlink($publicPath);
            }
            
            $this->image = null;
            $this->save();
        }
    }

    public function uploadImage($file)
    {
        // Delete old image if exists
        $this->deleteImage();
        
        // Generate a unique filename
        $filename = 'event-' . $this->id . '-' . time() . '.' . $file->getClientOriginalExtension();
        
        // Store in the 'events' directory within the public disk
        $path = $file->storeAs('events', $filename, 'public');
        
        // Update the image column with the path
        $this->image = $path;
        $this->save();
        
        // Log successful upload
        Log::info('Image uploaded for event ' . $this->id . ': ' . $path);
        
        return $path;
    }

    public function hasImage()
    {
        return !empty($this->image);
    }

    /**
     * Save quietly without triggering events
     */
  /**
 * Save quietly without triggering events
 */
public function saveQuietly(array $options = [])
{
    return static::withoutEvents(function () use ($options) {
        return $this->save($options);
    });
}

    /**
     * Speaker Sync
     */
    public function syncSpeakers($speakerData)
    {
        $syncData = [];
        
        foreach ($speakerData as $data) {
            if (isset($data['speaker_id'])) {
                $syncData[$data['speaker_id']] = [
                    'session_title' => $data['session_title'] ?? null,
                    'session_time' => $data['session_time'] ?? null,
                    'session_duration' => $data['session_duration'] ?? null,
                    'session_description' => $data['session_description'] ?? null,
                    'order' => $data['order'] ?? 0,
                    'is_keynote' => $data['is_keynote'] ?? false,
                    'is_moderator' => $data['is_moderator'] ?? false,
                    'is_panelist' => $data['is_panelist'] ?? false,
                    'custom_data' => isset($data['custom_data']) ? json_encode($data['custom_data']) : null,
                ];
            }
        }
        
        $this->speakers()->sync($syncData);
    }
}
