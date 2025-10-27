<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuelManagement extends Model
{
  use HasFactory;

  protected $table = 'fuel_management';

  protected $fillable = [
    'vendor_id',
    'fuel_type_id',
    'tank_id',
    'qty',
    'rate',
    'amount',
    'delivery_date',
    'freight_charges',
    'remarks',
    'slip_image', // Added slip_image to fillable
  ];

  // Helper method to get the full slip image URL
  public function getSlipImageUrlAttribute()
  {
    if (!$this->slip_image) {
      return null;
    }

    return asset('storage/' . $this->slip_image);
  }

  /**
   * Check if fuel management has a slip image
   *
   * @return bool
   */
  public function hasSlipImage()
  {
    return !empty($this->slip_image);
  }

  // Relationships
  public function vendor()
  {
    return $this->belongsTo(Party::class);
  }

  public function fuelType()
  {
    return $this->belongsTo(FuelType::class);
  }

  public function tank()
  {
    return $this->belongsTo(Tank::class);
  }
}
