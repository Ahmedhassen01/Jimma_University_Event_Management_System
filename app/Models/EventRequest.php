<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class EventRequest extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'proposed_start_date',
        'proposed_end_date',
        'proposed_venue',
        'proposed_campus',
        'event_type',
        'organizer_name',
        'organizer_email',
        'organizer_phone',
        'expected_attendees',
        'additional_requirements',
        'status',
        'reviewed_by',
        'review_notes',
        'reviewed_at',
        'event_id',
        'venue_id',
    'campus_id',
    'building_id',
    'alternative_venue',
    ];

    protected $casts = [
        'proposed_start_date' => 'datetime',
        'proposed_end_date' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // Status scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeManagerReview($query)
    {
        return $query->where('status', 'manager_review');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeManagerRejected($query)
    {
        return $query->where('status', 'manager_rejected');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    // Status helpers
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isManagerReview()
    {
        return $this->status === 'manager_review';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function isManagerRejected()
    {
        return $this->status === 'manager_rejected';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    // Get status color
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'manager_review' => 'info',
            'manager_rejected' => 'danger',
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'cancelled' => 'secondary',
            default => 'secondary',
        };
    }

    // Get status badge
    public function getStatusBadgeAttribute()
    {
        return '<span class="badge bg-' . $this->status_color . '">' . ucfirst($this->status) . '</span>';
    }


    // Add these to your EventRequest model:
public function venueRelation()
{
    return $this->belongsTo(Venue::class, 'venue_id');
}

public function campusRelation()
{
    return $this->belongsTo(Campus::class, 'campus_id');
}

public function buildingRelation()
{
    return $this->belongsTo(Building::class, 'building_id');
}

public function getAdditionalRequirementsTextAttribute(): ?string
{
    if (empty($this->additional_requirements)) {
        return null;
    }

    $decoded = json_decode($this->additional_requirements, true);

    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
        return $decoded['notes']
            ?? $decoded['additional_requirements']
            ?? null;
    }

    return $this->additional_requirements;
}

public function getRequestedImagePathAttribute(): ?string
{
    if (empty($this->additional_requirements)) {
        return null;
    }

    $decoded = json_decode($this->additional_requirements, true);

    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
        return $decoded['event_image'] ?? null;
    }

    return null;
}

public function getRequestedImageUrlAttribute(): ?string
{
    $path = $this->requested_image_path;

    if (!$path) {
        return null;
    }

    if (!Route::has('event-requests.image')) {
        return asset('storage/' . ltrim($path, '/'));
    }

    return route('event-requests.image', ['eventRequest' => $this->id]);
}

}
