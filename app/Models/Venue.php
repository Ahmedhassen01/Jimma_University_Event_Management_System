<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venue extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'building_id', 'type', 'capacity',
        'description', 'amenities', 'booking_fee', 'is_available',
        'requires_approval', 'available_hours'
    ];

    protected $casts = [
        'amenities' => 'array',
        'available_hours' => 'array',
        'is_available' => 'boolean',
        'requires_approval' => 'boolean',
        'booking_fee' => 'decimal:2',
    ];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    // Update this relationship - Events are linked by venue name, not foreign key
    public function events()
    {
        return $this->hasMany(Event::class, 'venue', 'name');
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function hasAmenity($amenity)
    {
        return in_array($amenity, $this->amenities ?? []);
    }
}