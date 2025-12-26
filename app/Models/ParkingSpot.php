<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingSpot extends Model
{
    use HasFactory;

    protected $fillable = [
        'label', 
        'status', 
        'price', 
        'sensor_id'
    ];
}
?>