<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\ParkingSpot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    // Méthode pour Créer une Réservation
    public function store(Request $request)
    {
        // 1. Validation des données reçues du Frontend
        $request->validate([
            'spot_id' => 'required|exists:parking_spots,id',
            'transaction_hash' => 'required|string|size:66', // 0x + 64 caractères
        ]);

        $user = $request->user(); // L'utilisateur connecté (via Sanctum)

        // 2. Vérifier si la place est VRAIMENT libre
        // On utilise une "Transaction DB" pour éviter que 2 personnes réservent en même temps
        return DB::transaction(function () use ($request, $user) {
            
            // On verrouille la ligne pour être sûr d'être le seul à lire
            $spot = ParkingSpot::lockForUpdate()->find($request->spot_id);

            if ($spot->status !== 'free') {
                return response()->json(['error' => 'Désolé, cette place n\'est plus disponible.'], 409);
            }

            // 3. Créer l'enregistrement de réservation
            Reservation::create([
                'user_id' => $user->id,
                'parking_spot_id' => $spot->id,
                'transaction_hash' => $request->transaction_hash,
                'start_time' => now(),
                'status' => 'active'
            ]);

            // 4. Mettre à jour le statut de la place (Elle devient Orange/Réservée)
            $spot->update(['status' => 'reserved']);

            return response()->json(['message' => 'Réservation confirmée !'], 201);
        });
    }

    // Méthode pour voir "Mes Réservations" (Historique)
    public function myHistory(Request $request)
    {
        // Récupère les résas de l'user connecté, avec les détails de la place
        $history = $request->user()->reservations()->with('spot')->latest()->get();
        return response()->json($history);
    }
}
// ===========================================================
// ===========================================================
// Explanation:
// J'ai utilisé `DB::transaction` et `lockForUpdate()`.
//  C'est très important pour un système de réservation.
//   Imaginez que deux utilisateurs cliquent sur "Payer" à la même milliseconde.
//    Sans cela, le système pourrait vendre la même place deux fois. 
//    Ici, Laravel gère la file d'attente.
// ===========================================================
// ===========================================================






























?>