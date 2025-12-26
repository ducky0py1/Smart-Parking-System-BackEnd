<?php

namespace App\Http\Controllers;

use App\Models\ParkingSpot;
use Illuminate\Http\Request;

class IoTController extends Controller
{
    // 1. Le Pico demande : "Quel est le statut de ma place ?"
    // GET /api/device/{id}/status
    public function checkStatus($id)
    {
        $spot = ParkingSpot::find($id);
        
        if (!$spot) {
            return response()->json(['error' => 'Place inconnue'], 404);
        }

        // On renvoie juste le statut pour que le Pico sache quoi afficher sur l'LCD
        return response()->json([
            'id' => $spot->id,
            'status' => $spot->status // 'free', 'reserved', ou 'occupied'
        ]);
    }

    // 2. Le Pico signale : "Une voiture vient d'arriver (ou partir)"
    // POST /api/device/{id}/update
    public function updateStatus(Request $request, $id)
    {
        $spot = ParkingSpot::find($id);
        
        if (!$spot) {
            return response()->json(['error' => 'Place inconnue'], 404);
        }

        // On attend un booléen : is_occupied (true = voiture là, false = voiture partie)
        $isOccupied = $request->input('is_occupied');

        if ($isOccupied) {
            // Voiture détectée
            $spot->update(['status' => 'occupied']);
            // Optionnel : Ici, on pourrait vérifier si une réservation existait
        } else {
            // Voiture partie -> La place redevient libre
            $spot->update(['status' => 'free']);
            
            // On ferme les réservations actives sur cette place
            $spot->reservations()->where('status', 'active')->update([
                'status' => 'completed',
                'end_time' => now()
            ]);
        }

        return response()->json(['message' => 'Statut mis à jour', 'new_status' => $spot->status]);
    }
}

























?>