<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description'
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function permissions(): BelongsToMany
    {
        // FIX: Explicitly define the pivot table name
        return $this->belongsToMany(Permission::class, 'role_permission')
                    ->withTimestamps();
    }

    // Helper method to check if role has permission
    public function hasPermission($permissionSlug): bool
    {
        // Use the relationship to check
        return $this->permissions()->where('slug', $permissionSlug)->exists();
    }
}