<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'parkig_spot_id',
        'transaction_hash',
        'start_time',
        'end_time',
        'status'
    ];

    //Relation : Une réservation appartient à un User
    public function user(){
        return $this-> belongsTo(User::class);
    }
     // Relation : Une réservation concerne une Place
     public function spot(){
        return $this->belongsTo(ParkingSpot::class,'parking_spot_id');
        
     }
}
