<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportLogistic extends Model
{
  use HasFactory;

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
    'has_extra_routes',
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
    'has_extra_routes' => 'boolean',
  ];

  /**
   * Get the export job that owns the logistics record.
   */
  public function exportJob()
  {
    return $this->belongsTo(ExportJob::class);
  }

  /**
   * Get the vehicle assigned to this logistics record.
   */
  public function vehicle()
  {
    return $this->belongsTo(Fleet::class, 'vehicle_id');
  }

  /**
   * Get the route assigned to this logistics record.
   */
  public function route()
  {
    return $this->belongsTo(Route::class);
  }

  /**
   * Get the extra routes associated with this export logistics.
   */
  public function extraRoutes()
  {
    return $this->hasMany(ExtraRoute::class, 'reference_id')->where('reference_type', 'export_logistics');
  }
}
