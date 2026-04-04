<?php

namespace App\Http\Controllers;

use App\Models\ParkingSpot;
use Illuminate\Http\Request;
use Carbon\Carbon;
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

           // LA VOITURE S'EN VA : Calcul du dépassement
            
            // 1. Trouver la réservation active pour cette place
            $reservation = Reservation::where('parking_spot_id', $id)
                                      ->where('status', 'active')
                                      ->first();

            if ($reservation) {
                $now = Carbon::now();
                // Assurez-vous que 'end_time' est bien casté en date dans le modèle Reservation
                $expectedEndTime = Carbon::parse($reservation->end_time);

                // 2. Vérifier s'il y a un dépassement (Overtime)
                if ($now->greaterThan($expectedEndTime)) {
                    // Calcul de la durée en minutes
                    $minutesOver = $now->diffInMinutes($expectedEndTime);
                    
                    // Calcul du prix par minute (Basé sur le prix de la place / 60)
                    $pricePerMinute = $spot->price / 60;
                    $penaltyAmount = $minutesOver * $pricePerMinute;

                    // 3. Ajouter la dette à l'utilisateur
                    $user = $reservation->user;
                    $user->debt += $penaltyAmount;
                    $user->save();
                }

                // 4. Clôturer la réservation
                $reservation->update([
                    'status' => 'completed',
                    'actual_end_time' => $now // ajt cet collone
                ]);
            }

            // 5. Libérer la place
            $spot->update(['status' => 'free']);   
        }

        return response()->json([
            'message' => 'Statut mis à jour', 
            'new_status' => $spot->status
        ]);
    }

}





// Explications techniques du calcul :
// Carbon::parse($reservation->end_time) : Transforme la chaîne de caractères de la base de données en un objet Date manipulable.
// greaterThan : Vérifie si l'heure actuelle est après l'heure limite payée.
// diffInMinutes : Calcule précisément le nombre de minutes de retard.
// Dette : On multiplie les minutes de retard par le prix à la minute. Cette dette est ajoutée au champ debt de la table users.


















?>