<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobComment extends Model
{
  use HasFactory;

  protected $fillable = [
    'type',
    'job_id',
    'status',
    'comment',
    'user_id',
  ];

  // Relationship with User
  public function user()
  {
    return $this->belongsTo(User::class);
  }

  // Relationship with JobQueue (import job)
  public function jobQueue()
  {
    return $this->belongsTo(JobQueue::class, 'job_id')
      ->when($this->type === 'import', function ($query) {
        return $query;
      });
  }

  // Relationship with ExportJob
  public function exportJob()
  {
    return $this->belongsTo(ExportJob::class, 'job_id')
      ->when($this->type === 'export', function ($query) {
        return $query;
      });
  }

  // Generic job relationship that returns appropriate job based on type
  public function job()
  {
    return $this->type === 'export'
      ? $this->exportJob()
      : $this->jobQueue();
  }

  // Scope for import job comments
  public function scopeImport($query)
  {
    return $query->where('type', 'import');
  }

  // Scope for export job comments
  public function scopeExport($query)
  {
    return $query->where('type', 'export');
  }
}
