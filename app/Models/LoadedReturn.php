<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoadedReturn extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'loaded_return';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'export_job_id',
    'market_vehicle',
    'market_vehicle_details',
    'vehicle_id',
    'gate_pass',
    'gate_time_passed',
    'route_id',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'gate_time_passed' => 'datetime',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
  ];

  /**
   * Get the export job that owns the loaded return.
   */
  public function exportJob()
  {
    return $this->belongsTo(ExportJob::class);
  }

  /**
   * Get the vehicle used for the loaded return.
   */
  public function vehicle()
  {
    return $this->belongsTo(Fleet::class, 'vehicle_id');
  }

  /**
   * Get the route used for the loaded return.
   */
  public function route()
  {
    return $this->belongsTo(Route::class);
  }
}
