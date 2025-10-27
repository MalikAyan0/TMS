<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issuance extends Model
{
    use HasFactory;

    protected $fillable = [
        'tank_id',
        'fleet_id',
        'operation_id',
        'fill_date',
        'qty',
        'driver',
        'odometer_reading',
        'remarks',
        'created_by',
    ];

    // Relations
    public function tank()
    {
        return $this->belongsTo(Tank::class);
    }

    public function fleet()
    {
        return $this->belongsTo(Fleet::class);
    }

    public function operation()
    {
        return $this->belongsTo(Operation::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
