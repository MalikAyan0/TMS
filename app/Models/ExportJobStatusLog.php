<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportJobStatusLog extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'export_job_id',
    'status',
    'remarks',
    'changed_by',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
  ];

  /**
   * Get the export job that owns the status log.
   */
  public function exportJob()
  {
    return $this->belongsTo(ExportJob::class);
  }

  /**
   * Get the user who changed the status.
   */
  public function user()
  {
    return $this->belongsTo(User::class, 'changed_by');
  }

  /**
   * Get the previous status log for the same export job.
   *
   * @param int $exportJobId
   * @param int $currentLogId
   * @return ExportJobStatusLog|null
   */
  public static function getPreviousStatus(int $exportJobId, int $currentLogId)
  {
    return self::where('export_job_id', $exportJobId)
        ->where('id', '<', $currentLogId)
        ->orderBy('id', 'desc')
        ->first();
  }
}
