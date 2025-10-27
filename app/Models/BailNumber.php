<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BailNumber extends Model
{
  use HasFactory;

  protected $table = 'bail';

  protected $fillable = [
    'bail_number',
    'description',
    'status',
  ];

  protected $casts = [
    'status' => 'string',
  ];

  // Relationships
  public function jobQueues()
  {
    return $this->hasMany(JobQueue::class, 'bail_number_id');
  }

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
  public function getStatusBadgeAttribute()
  {
    return $this->status === 'active' ? 'success' : 'secondary';
  }
}
