<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetManufacturer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'created_by',
    ];

    // Relation with User (creator)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
