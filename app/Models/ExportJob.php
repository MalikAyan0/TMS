<?php

namespace App\Models;

use App\Models\JobComment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\ExportJobStatus;

class ExportJob extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'cro_id',
    'container',
    'size',
    'line_id',
    'forwarder_id',
    'pod_id',
    'terminal_id',
    'empty_pickup',
    'status',
    'job_type', // Added job_type to fillable
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    'status' => ExportJobStatus::class, // Cast status to ExportJobStatus enum
  ];

  /**
   * Get the CRO associated with the export job.
   */
  public function cro()
  {
    return $this->belongsTo(Cro::class);
  }


  /**
   * Get the shipping line associated with the export job.
   */
  public function line()
  {
    return $this->belongsTo(Line::class);
  }

  /**
   * Get the forwarder party associated with the export job.
   */
  public function forwarder()
  {
    return $this->belongsTo(Party::class, 'forwarder_id');
  }


  /**
   * Get the POD associated with the export job.
   */
  public function pod()
  {
    return $this->belongsTo(Pod::class);
  }

  /**
   * Get the terminal associated with the export job.
   */
  public function terminal()
  {
    return $this->belongsTo(Terminal::class);
  }

  /**
   * Get the empty pickup location associated with the export job.
   */
  public function emptyPickupLocation()
  {
    return $this->belongsTo(Location::class, 'empty_pickup');
  }

  /**
   * Get the logistics records associated with the export job.
   */
  public function logistics()
  {
    return $this->hasMany(ExportLogistic::class);
  }

  /**
   * Get the status logs for the export job.
   */
  public function statusLogs()
  {
    return $this->hasMany(ExportJobStatusLog::class);
  }

  /**
   * Get the latest status log for the export job.
   */
  public function latestStatusLog()
  {
    return $this->hasOne(ExportJobStatusLog::class)->latest();
  }

  /**
   * Get the loaded return record associated with the export job.
   */
  public function loadedReturn()
  {
    return $this->hasOne(LoadedReturn::class);
  }

  /**
   * Get comments for this job.
   */
  public function jobComments()
  {
    return $this->hasMany(JobComment::class, 'job_id', 'id');
  }

  // Compatibility method for existing code
  public function comments()
  {
    return $this->jobComments()->where('type', 'export');
  }

  /**
   * Scope a query to only include jobs with specific status.
   */
  public function scopeStatus($query, $status)
  {
    return $query->where('status', $status);
  }

  /**
   * Scope a query to only include open jobs.
   */
  public function scopeOpen($query)
  {
    return $query->where('status', 'Open');
  }

  /**
   * Scope a query to only include in progress jobs.
   */
  public function scopeInProgress($query)
  {
    return $query->where('status', 'Vehicle Required');
  }

  /**
   * Scope a query to only include completed jobs.
   */
  public function scopeCompleted($query)
  {
    return $query->where('status', 'Completed');
  }
}
