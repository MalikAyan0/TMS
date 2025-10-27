<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Location extends Model
{
  use SoftDeletes;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'title',
    'short_name',
    'description',
    'type',
    'status',
    'country',
    'city',
    'address',
    'postal_code',
    'latitude',
    'longitude',
    'created_by',
    'updated_by',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'latitude' => 'decimal:8',
    'longitude' => 'decimal:8',
  ];

  /**
   * Get the user who created this location.
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get the user who last updated this location.
   */
  public function updater(): BelongsTo
  {
    return $this->belongsTo(User::class, 'updated_by');
  }

  /**
   * Scope a query to only include active locations.
   */
  public function scopeActive($query)
  {
    return $query->where('status', 'active');
  }

  /**
   * Scope a query to filter by type.
   */
  public function scopeOfType($query, $type)
  {
    return $query->where('type', $type);
  }

  /**
   * Get the full address attribute.
   */
  public function getFullAddressAttribute()
  {
    $parts = array_filter([
      $this->address,
      $this->city,
      $this->country,
      $this->postal_code
    ]);

    return implode(', ', $parts);
  }

  /**
   * Get the coordinates attribute.
   */
  public function getCoordinatesAttribute()
  {
    if ($this->latitude && $this->longitude) {
      return [
        'lat' => (float) $this->latitude,
        'lng' => (float) $this->longitude
      ];
    }

    return null;
  }

  /**
   * Get the export jobs that use this location for empty pickup.
   */
  public function exportJobs()
  {
    return $this->hasMany(ExportJob::class, 'empty_pickup');
  }
}
