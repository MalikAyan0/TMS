<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesTaxTerritory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'head',
        'title',
        'short_name',
        'address',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Scope a query to only include active territories.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include inactive territories.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Get the badge class for the territory status.
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'active' => 'bg-label-success',
            'inactive' => 'bg-label-secondary',
            default => 'bg-label-secondary',
        };
    }

    /**
     * Get the icon for the territory status.
     */
    public function getStatusIconAttribute()
    {
        return match($this->status) {
            'active' => 'tabler-check',
            'inactive' => 'tabler-x',
            default => 'tabler-x',
        };
    }

    /**
     * Get the short name badge class based on status.
     */
    public function getShortNameBadgeClassAttribute()
    {
        return match($this->status) {
            'active' => 'bg-label-primary',
            'inactive' => 'bg-label-secondary',
            default => 'bg-label-secondary',
        };
    }
}
