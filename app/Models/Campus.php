<?php
// app/Models/Campus.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campus extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'description', 'location',
        'contact_email', 'contact_phone', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function buildings()
    {
        return $this->hasMany(Building::class);
    }

    public function events()
    {
        return $this->hasManyThrough(Event::class, Building::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}