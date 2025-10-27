<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobQueue extends Model
{
  use HasFactory;

  protected $table = 'jobs_queue';

  protected $fillable = [
    'job_number', // Job serial number
    'bail_number_id', // Foreign key to bail table
    'container',
    'company_id', // Foreign key to companies table
    'size', // Size of the job
    'line_id', // Foreign key to lines table
    'forwarder_id', // Foreign key to forwarders table
    'port_id', // Foreign key to ports table
    'noc_deadline', // NOC deadline for the job
    'eta', // Estimated time of arrival
    'mode', // Mode of the job (e.g., CFS, CY)
    'status', // Status of the job (e.g., pending, in-progress, completed, etc.)
  ];

  // Relationships
  // A job queue belongs to one line
  public function line()
  {
    return $this->belongsTo(Line::class, 'line_id');
  }
  // A job queue belongs to one forwarder
  public function forwarder()
  {
    return $this->belongsTo(Party::class, 'forwarder_id');
  }
  // A job queue belongs to one company
  public function company()
  {
    return $this->belongsTo(Company::class, 'company_id');
  }
  // A job queue belongs to one port
  public function port()
  {
    return $this->belongsTo(Port::class, 'port_id');
  }
  // A job queue belongs to one bail number
  public function bailNumber()
  {
    return $this->belongsTo(BailNumber::class, 'bail_number_id');
  }

  // A job queue has one logistics record
  public function logistics()
  {
    return $this->hasOne(JobLogistics::class, 'jobs_queue_id');
  }

  // Accessor for vehicle through logistics
  public function vehicle()
  {
    return $this->hasOneThrough(Fleet::class, JobLogistics::class, 'jobs_queue_id', 'id', 'id', 'vehicle_id');
  }

  // A job queue has many status logs
  public function statusLogs()
  {
    return $this->hasMany(JobStatusLog::class, 'jobs_queue_id');
  }

  // A job queue has one empty return record
  public function emptyReturn()
  {
    return $this->hasOne(JobEmptyReturn::class, 'jobs_queue_id');
  }

  /**
   * Get comments for this job.
   */
  public function jobComments()
  {
    return $this->hasMany(\App\Models\JobComment::class, 'job_id', 'id');
  }

  // Replace jobQueueComments method if it exists with:
  public function jobQueueComments()
  {
    return $this->jobComments()->where('type', 'import');
  }
}
