<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteCost extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id','cost_type','amount','description','receipt_file'
    ];

    public function assignment() {
        return $this->belongsTo(RouteTruckAssignment::class,'assignment_id');
    }
}
