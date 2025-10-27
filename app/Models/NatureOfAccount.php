<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NatureOfAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'code',
        'type',
        'description',
        'status'
    ];

    protected $casts = [
        'code' => 'integer'
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

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Accessors
    public function getTypeDisplayAttribute()
    {
        return ucfirst($this->type);
    }

    public function getStatusBadgeClassAttribute()
    {
        return $this->status === 'active' ? 'success' : 'secondary';
    }

    public function getTypeBadgeClassAttribute()
    {
        $typeColors = [
            'asset' => 'success',
            'liability' => 'danger',
            'equity' => 'info',
            'revenue' => 'primary',
            'expense' => 'warning'
        ];

        return $typeColors[$this->type] ?? 'secondary';
    }

    public function getTypeIconAttribute()
    {
        $typeIcons = [
            'asset' => 'building-bank',
            'liability' => 'credit-card',
            'equity' => 'chart-pie',
            'revenue' => 'trending-up',
            'expense' => 'trending-down'
        ];

        return $typeIcons[$this->type] ?? 'file-text';
    }
}
