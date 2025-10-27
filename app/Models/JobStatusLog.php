<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobStatusLog extends Model
{
  use HasFactory;

  protected $table = 'job_status_logs';

  protected $fillable = [
    'jobs_queue_id',
    'status',
    'remarks',
    'changed_by',
  ];

  // Relationships
  public function jobQueue()
  {
    return $this->belongsTo(JobQueue::class, 'jobs_queue_id');
  }

  public function changedBy()
  {
    return $this->belongsTo(User::class, 'changed_by');
  }
}
