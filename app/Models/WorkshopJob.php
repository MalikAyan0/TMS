<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkshopJob extends Model
{
  use HasFactory;

  protected $table = 'workshop_jobs';

  protected $fillable = [
    'parts_detail',
    'invoice',
    'vehicle_id',
    'reconciliation',
    'location',
    'vendor_name',
    'slip_image',
    'status',
    'description',
    'type'
  ];

  protected $casts = [
    'reconciliation' => 'decimal:2',
    'paid_by_terminal' => 'string',
  ];

  /**
   * Relationship with Fleet (vehicle)
   */
  public function vehicle()
  {
    return $this->belongsTo(Fleet::class, 'vehicle_id');
  }
}
