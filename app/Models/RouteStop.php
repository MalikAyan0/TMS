<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteStop extends Model
{
  use HasFactory;

  protected $fillable = [
    'route_id',
    'stop_order',
    'location_name',
    'load',
    'load_type',
    'arrival_time',
    'departure_time',
    'actual_arrival_time',
    'actual_departure_time'
  ];

  public function route()
  {
    return $this->belongsTo(Route::class);
  }
}
