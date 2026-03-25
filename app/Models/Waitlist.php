<?php
// app/Models/Waitlist.php - COMPLETE FIXED VERSION

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Waitlist extends Model
{
    protected $fillable = [
        'event_id',
        'user_id',
        'position',
        'joined_at',
        'notified_at',
        'converted_at',
        'notes',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'notified_at' => 'datetime',
        'converted_at' => 'datetime',
        'position' => 'integer',
    ];

    /**
     * Get the event that owns the waitlist entry.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the user that owns the waitlist entry.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include active waitlist entries.
     */
    public function scopeActive($query)
    {
        return $query->whereNull('converted_at');
    }

    /**
     * Scope a query to only include converted waitlist entries.
     */
    public function scopeConverted($query)
    {
        return $query->whereNotNull('converted_at');
    }

    /**
     * Check if waitlist entry is active.
     */
    public function isActive()
    {
        return is_null($this->converted_at);
    }

    /**
     * Check if waitlist entry is converted.
     */
    public function isConverted()
    {
        return !is_null($this->converted_at);
    }

    /**
     * Get the position badge.
     */
    public function getPositionBadgeAttribute()
    {
        if ($this->position <= 3) {
            $class = 'danger';
        } elseif ($this->position <= 10) {
            $class = 'warning';
        } else {
            $class = 'info';
        }
        
        return '<span class="badge bg-' . $class . '">#' . $this->position . '</span>';
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($waitlist) {
            if (empty($waitlist->joined_at)) {
                $waitlist->joined_at = now();
            }
        });
    }
}