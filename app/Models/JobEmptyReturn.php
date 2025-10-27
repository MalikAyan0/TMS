<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobEmptyReturn extends Model
{
  use HasFactory;

  protected $table = 'job_empty_returns';

  protected $fillable = [
    'jobs_queue_id',
    'market_vehicle',
    'market_vehicle_details',
    'vehicle_id',
    'gate_pass',
    'gate_time_passed',
    'route_id',
    'has_extra_routes',
    'reason',
    'added_by',
    'added_at',
    'reference_id',
    'reference_type',
    'job_id',
  ];

  protected $casts = [
    'gate_time_passed' => 'datetime',
    'has_extra_routes' => 'boolean',
  ];

  // Relationships
  public function jobQueue()
  {
    return $this->belongsTo(JobQueue::class, 'jobs_queue_id');
  }

  public function vehicle()
  {
    return $this->belongsTo(Fleet::class, 'vehicle_id');
  }

  public function route()
  {
    return $this->belongsTo(Route::class, 'route_id');
  }

  /**
   * Get the extra routes associated with this empty return.
   */
  public function extraRoutes()
  {
    return $this->hasMany(ExtraRoute::class, 'reference_id')->where('reference_type', 'job_empty_return');
  }
}
