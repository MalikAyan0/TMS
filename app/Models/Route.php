<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
  use HasFactory;

  protected $fillable = [
    'route_name',
    'route_code',
    'origin',
    'destination',
    'load',
    'expected_fuel',
    'status',
    'created_by'
  ];

  public function stops()
  {
    return $this->hasMany(RouteStop::class);
  }

  public function assignments()
  {
    return $this->hasMany(RouteTruckAssignment::class);
  }
}
