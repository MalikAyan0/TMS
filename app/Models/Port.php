<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Port extends Model
{
  //
  protected $fillable = ['name', 'description', 'status'];
  protected $casts = [
    'status' => 'string',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
  ];

  // A port can have many job queues
  public function jobQueues()
  {
    return $this->hasMany(JobQueue::class, 'port_id');
  }

  public function scopeActive($query)
  {
    return $query->where('status', 'active');
  }
}
