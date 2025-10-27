<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobLogistics extends Model
{
  use HasFactory;

  protected $table = 'job_logistics';

  protected $fillable = [
    'jobs_queue_id',
    'market_vehicle',
    'market_vehicle_details',
    'vehicle_id',
    'gate_pass',
    'gate_time_passed',
    'route_id',
  ];

  protected $casts = [
    'gate_time_passed' => 'datetime',
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
}
