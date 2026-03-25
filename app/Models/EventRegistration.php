<?php
// app/Models/EventRegistration.php - COMPLETE FIXED VERSION

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class EventRegistration extends Model
{
    protected $fillable = [
        'event_id', 
        'user_id', 
        'registration_number', 
        'status',
        'guest_count', 
        'additional_info', 
        'registration_date',
        'confirmed_at', 
        'cancelled_at', 
        'cancellation_reason',
        'attended', 
        'check_in_time', 
        'notes'
    ];

    protected $casts = [
        'registration_date' => 'datetime',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'check_in_time' => 'datetime',
        'attended' => 'boolean',
        'guest_count' => 'integer',
    ];

    // Relationships
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeWaitlisted($query)
    {
        return $query->where('status', 'waitlisted');
    }

    public function scopeAttended($query)
    {
        return $query->where('attended', true);
    }

    public function scopeForEvent($query, $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Status helpers
    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function isWaitlisted()
    {
        return $this->status === 'waitlisted';
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'confirmed' => 'success',
            'pending' => 'warning',
            'cancelled' => 'danger',
            'waitlisted' => 'info',
            default => 'secondary',
        };
    }

    public function getStatusBadgeAttribute()
    {
        return '<span class="badge bg-' . $this->status_color . '">' . ucfirst($this->status) . '</span>';
    }

    /**
     * Generate a unique registration number.
     */
    protected static function generateRegistrationNumber()
    {
        return 'REG-' . date('Ymd') . '-' . strtoupper(Str::random(8));
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($registration) {
            if (!$registration->registration_number) {
                $registration->registration_number = self::generateRegistrationNumber();
            }
            
            if (!$registration->registration_date) {
                $registration->registration_date = now();
            }
        });
    }
}