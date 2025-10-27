<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteTruckAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id','fleet_id','start_date','expected_end_date',
        'status','expected_fuel','expected_cost','actual_fuel_used','actual_cost'
    ];

    public function route() {
        return $this->belongsTo(Route::class);
    }

    public function fleet() {
        return $this->belongsTo(fleet::class);
    }

    // public function driver() {
    //     return $this->belongsTo(Driver::class);
    // }

    public function costs() {
        return $this->hasMany(RouteCost::class,'assignment_id');
    }
}
