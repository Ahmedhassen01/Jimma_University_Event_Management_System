<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Feedback extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'event_id',
        'user_id',
        'name',
        'email',
        'type',
        'subject',
        'message',
        'rating',
        'status',
        'assigned_to',
        'admin_notes',
        'resolution_notes',
        'reviewed_at',
        'resolved_at',
        'allow_contact',
        'is_public',
        'featured',
        'metadata',
    ];

    protected $casts = [
        'allow_contact' => 'boolean',
        'is_public' => 'boolean',
        'featured' => 'boolean',
        'rating' => 'integer',
        'metadata' => 'array',
        'reviewed_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(FeedbackResponse::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(FeedbackCategory::class, 'feedback_category');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeReviewed($query)
    {
        return $query->where('status', 'reviewed');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeWithRating($query)
    {
        return $query->whereNotNull('rating');
    }

    // Methods
    public function markAsReviewed()
    {
        $this->update([
            'status' => 'reviewed',
            'reviewed_at' => now(),
        ]);
    }

    public function markAsResolved($notes = null)
    {
        $this->update([
            'status' => 'resolved',
            'resolved_at' => now(),
            'resolution_notes' => $notes,
        ]);
    }

    public function getSubmitterName(): string
    {
        return $this->user ? $this->user->name : ($this->name ?: 'Anonymous');
    }

    public function getRatingStars(): string
    {
        if (!$this->rating) return '';
        
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            $stars .= $i <= $this->rating ? '★' : '☆';
        }
        return $stars;
    }
}