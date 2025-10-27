<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OilType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'label',
        'status'
    ];

    protected $casts = [
        'status' => 'string',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    // Accessors
    public function getIsActiveAttribute()
    {
        return $this->status === 'active';
    }
}
