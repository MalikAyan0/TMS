<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobType extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'short_name',
        'description',
        'status'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope a query to only include active job types.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include inactive job types.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Get the badge class for the job type status.
     */
    public function getStatusBadgeClassAttribute()
    {
        return match ($this->status) {
            'active' => 'bg-label-success',
            'inactive' => 'bg-label-secondary',
            default => 'bg-label-secondary',
        };
    }

    /**
     * Get the icon for the job type status.
     */
    public function getStatusIconAttribute()
    {
        return match ($this->status) {
            'active' => 'ti tabler-check',
            'inactive' => 'ti tabler-x',
            default => 'ti tabler-x',
        };
    }

    /**
     * Get the short name badge class.
     */
    public function getShortNameBadgeClassAttribute()
    {
        return $this->status === 'active' ? 'bg-label-primary' : 'bg-label-secondary';
    }
}
