<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tank extends Model
{
  use HasFactory;

  protected $table = 'tanks';

  protected $fillable = [
    'fuel_type_id',
    'name',
    'supervisor_id',
    'location',
    'capacity_volume',
    'remarks',
    'status',
  ];

  // Relationships
  public function fuelType()
  {
    return $this->belongsTo(FuelType::class);
  }

  public function fuelManagements()
  {
    return $this->hasMany(FuelManagement::class, 'tank_id');
  }

  public function supervisor()
  {
    return $this->belongsTo(User::class, 'supervisor_id');
  }
}
