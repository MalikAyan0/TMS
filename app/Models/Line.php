<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Line extends Model
{
  //
  protected $fillable = ['name', 'description', 'status'];
  protected $casts = [
    'status' => 'string',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
  ];

  // A line can have many job queues
  public function jobQueues()
  {
    return $this->hasMany(JobQueue::class, 'line_id');
  }

  public function scopeActive($query)
  {
    return $query->where('status', 'active');
  }
}
