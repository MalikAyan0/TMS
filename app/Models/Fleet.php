<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fleet extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'fleet_manufacturer_id',
    'fleet_type_id',
    'first_driver',
    'second_driver',
    'registration_number',
    'registration_city',
    'ownership',
    'diesel_opening_inventory',
    'created_by',
  ];

  // Relations
  public function manufacturer()
  {
    return $this->belongsTo(FleetManufacturer::class, 'fleet_manufacturer_id');
  }

  public function type()
  {
    return $this->belongsTo(FleetType::class, 'fleet_type_id');
  }

  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  public function jobQueue()
  {
    return $this->hasMany(JobQueue::class, 'job_queue_id');
  }

  /**
   * Get the loaded returns associated with this vehicle.
   */
  public function loadedReturns()
  {
    return $this->hasMany(LoadedReturn::class, 'vehicle_id');
  }
}
