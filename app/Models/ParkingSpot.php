<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class parkingSpot extends Model{
    use HasFactory;

    protected $fillable =[
        'label',
        'status',
        'price',
        'sensor_id',
    ];
}




?>