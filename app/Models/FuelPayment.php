<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Support\Facades\Auth;

class FuelPayment extends Model
{
  use HasFactory;

  protected $table = 'fuel_payments';

  protected $fillable = [
    'reference_id',
    'reference_type',
    'fleet_id',
    'tank_id',
    'payment_status',
    'payment_date',
    'payment_notes'
  ];

  protected $casts = [
    'payment_date' => 'date',
  ];

  // Relationships
  public function fleet()
  {
    return $this->belongsTo(Fleet::class, 'fleet_id');
  }

  // New relationship for tank
  public function tank()
  {
    return $this->belongsTo(Tank::class, 'tank_id');
  }

  // Polymorphic relationship to get the related record (logistics or empty return)
  public function referenceable()
  {
    return $this->morphTo('reference', 'reference_type', 'reference_id');
  }

  // Get logistics record when reference_type is 'logistics'
  public function logistics()
  {
    return $this->belongsTo(JobLogistics::class, 'reference_id')
      ->where('reference_type', 'logistics');
  }

  // Get empty return record when reference_type is 'empty_return'
  public function emptyReturn()
  {
    return $this->belongsTo(JobEmptyReturn::class, 'reference_id')
      ->where('reference_type', 'empty_return');
  }

  // Scopes
  public function scopePaid($query)
  {
    return $query->where('payment_status', 'paid');
  }

  public function scopeUnpaid($query)
  {
    return $query->where('payment_status', 'unpaid');
  }

  public function scopeLogistics($query)
  {
    return $query->where('reference_type', 'logistics');
  }

  public function scopeEmptyReturn($query)
  {
    return $query->where('reference_type', 'empty_return');
  }

  public function scopeForFleet($query, $fleetId)
  {
    return $query->where('fleet_id', $fleetId);
  }

  // New scope for tank
  public function scopeForTank($query, $tankId)
  {
    return $query->where('tank_id', $tankId);
  }

  // Helper methods
  public function isPaid()
  {
    return $this->payment_status === 'paid';
  }

  public function isUnpaid()
  {
    return $this->payment_status === 'unpaid';
  }

  public function markAsPaid($paymentDate = null, $notes = null)
  {
    $this->update([
      'payment_status' => 'paid',
      'payment_date' => $paymentDate ?? now()->toDateString(),
      'payment_notes' => $notes
    ]);
  }

  public function markAsUnpaid($notes = null)
  {
    $this->update([
      'payment_status' => 'unpaid',
      'payment_date' => null,
      'payment_notes' => $notes
    ]);
  }

  /**
   * Mark payment as paid with tank details.
   *
   * @param int $tankId The tank ID
   * @param string|null $paymentDate Payment date (defaults to today)
   * @param string|null $notes Payment notes
   * @return bool
   */
  public function markAsPaidWithTank($tankId, $paymentDate = null, $notes = null)
  {
    $tank = Tank::findOrFail($tankId);

    // Get the fuel amount used
    $fuelAmount = $this->getFuelAmount();

    // Check if tank has sufficient fuel
    if ($tank->available_fuel < $fuelAmount) {
      return false;
    }

    // Update the tank's available fuel
    $tank->decrement('available_fuel', $fuelAmount);

    // Mark payment as paid
    $this->update([
      'payment_status' => 'paid',
      'payment_date' => $paymentDate ?? now()->toDateString(),
      'payment_notes' => $notes,
      'tank_id' => $tankId
    ]);

    return true;
  }

  /**
   * Get the amount of fuel used for this payment.
   *
   * @return float
   */
  public function getFuelAmount()
  {
    if ($this->reference_type === 'logistics') {
      $logistics = JobLogistics::find($this->reference_id);
      if ($logistics && $logistics->route) {
        return $logistics->route->expected_fuel ?? 0;
      }
    } else if ($this->reference_type === 'empty_return') {
      $emptyReturn = JobEmptyReturn::find($this->reference_id);
      if ($emptyReturn && $emptyReturn->route) {
        return $emptyReturn->route->expected_fuel ?? 0;
      }
    }

    return 0;
  }

  /**
   * Get user's available tanks with sufficient fuel for this payment.
   *
   * @param int|null $userId The user ID (defaults to authenticated user)
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public static function getAvailableTanksForUser($userId = null)
  {
    $userId = $userId ?? Auth::id();

    // Get all tanks where the user is the supervisor
    $tanks = Tank::where('supervisor_id', $userId)
      ->where('status', 'active')
      ->get();

    return $tanks;
  }

  /**
   * Get available tanks for the authenticated user.
   *
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public static function getAuthUserTanks()
  {
    return self::getAvailableTanksForUser(Auth::id());
  }

  /**
   * Get detailed information about tanks assigned to the authenticated user.
   *
   * @return array
   */
  public static function getAuthUserTankDetails()
  {
    $tanks = self::getAuthUserTanks();
    $tanksDetails = [];

    foreach ($tanks as $tank) {
      $tanksDetails[] = self::getTankDetails($tank->id);
    }

    return $tanksDetails;
  }

  /**
   * Get detailed information about a tank.
   *
   * @param int $tankId The tank ID
   * @return array|null
   */
  public static function getTankDetails($tankId)
  {
    $tank = Tank::with(['fuelType', 'supervisor', 'fuelManagements'])->find($tankId);

    if (!$tank) return null;

    // Calculate total quantity added from fuelManagements
    $totalFuelAdded = $tank->fuelManagements->sum('qty');

    // Calculate total fuel used from fuelPayments
    $totalFuelUsed = FuelPayment::where('tank_id', $tankId)
      ->where('payment_status', 'paid')
      ->get()
      ->sum(function ($payment) {
        return $payment->getFuelAmount();
      });

    // Calculate available fuel
    $availableFuel = $totalFuelAdded - $totalFuelUsed;

    return [
      'id' => $tank->id,
      'name' => $tank->name,
      'fuel_type' => $tank->fuelType ? $tank->fuelType->label : 'Unknown',
      'supervisor' => $tank->supervisor ? $tank->supervisor->name : 'Unassigned',
      'capacity_volume' => $tank->capacity_volume,
      'total_fuel_added' => $totalFuelAdded,
      'total_fuel_used' => $totalFuelUsed,
      'available_fuel' => $availableFuel,
      'location' => $tank->location,
      'status' => $tank->status
    ];
  }
}
