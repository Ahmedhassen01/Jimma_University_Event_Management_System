<?php
// app/Models/Building.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Building extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'campus_id', 'code', 'description',
        'floors', 'contact_person', 'contact_phone', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function venues()
    {
        return $this->hasMany(Venue::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}