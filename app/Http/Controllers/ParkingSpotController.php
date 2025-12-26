<?php

namespace App\Http\Controllers;

use App\Models\ParkingSpot;
use Illuminate\Http\Request;

class ParkingSpotController extends Controller
{
    // Récupérer toutes les places (Pour la carte React)
    public function index()
    {
        // On récupère tout : id, label, status, price
        $spots = ParkingSpot::all();
        return response()->json($spots);
    }

    // Récupérer une seule place (Détails)
    public function show($id)
    {
        $spot = ParkingSpot::find($id);

        if (!$spot) {
            return response()->json(['error' => 'Place introuvable'], 404);
        }

        return response()->json($spot);
    }
}

?>