<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParkingSpot;

class ParkingSpotController extends Controller
{
    // Récupérer toutes les places (pour la carte React)
    public function index(){
        $spots = parkingSpot::all();
        return response()->json($spots);
    }
        // Récupérer une seule place (détails)
    public function show($id){
        $spot = ParkingSpot::ind($id);
        if(!$spot){
            return response()->json(['message' => 'Place introuvable'],404);

        }
        return response()->json($spot);
    }
}
    //

