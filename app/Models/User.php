<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // 

class User extends Authenticatable
{
    /**
     * Les attributs qu'on peut remplir via User::create() ou update()
     */
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'wallet_address', // Notre identifiant
        'first_name',
        'last_name',
        'email',
    ];
    //relation: a user could have many reservations
    public function reservation(){
        return $this->hasMany(Reservation::class);
    }
}
?>