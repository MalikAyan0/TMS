<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraRoute extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'reference_id',
    'reference_type',
    'job_id',
    'route_id',
    'vehicle_id',
    'reason',
    'assigned_by',
  ];

  /**
   * Get the parent referenceable model (either export logistics or job empty return).
   */
  public function reference()
  {
    return $this->morphTo();
  }

  /**
   * Get the route for this extra route.
   */
  public function route()
  {
    return $this->belongsTo(Route::class);
  }

  /**
   * Get the user who assigned this extra route.
   */
  public function assignedBy()
  {
    return $this->belongsTo(User::class, 'assigned_by');
  }

  /**
   * Get the job this extra route is associated with.
   * This can be either ExportJob or JobQueue depending on the reference_type.
   */
  public function job()
  {
    if ($this->reference_type === 'export_logistics') {
      return $this->belongsTo(ExportJob::class, 'job_id');
    }

    return $this->belongsTo(JobQueue::class, 'job_id');
  }

  /**
   * Get the vehicle associated with the extra route.
   */
  public function vehicle()
  {
    return $this->belongsTo(Fleet::class, 'vehicle_id');
  }
}
