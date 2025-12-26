<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ParkingSpotController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\IoTController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// --- ZONE PUBLIQUE (Frontend & IoT) ---

// 1. Auth Web3
Route::post('/auth/wallet', [AuthController::class, 'loginWithWallet']);

// 2. Gestion des Places (Frontend)
Route::get('/spots', [ParkingSpotController::class, 'index']);
Route::get('/spots/{id}', [ParkingSpotController::class, 'show']);

// 3. IoT (Raspberry Pi Pico)
// Le Pico demande l'état pour l'afficher sur l'LCD
Route::get('/device/{id}/status', [IoTController::class, 'checkStatus']);
// Le Pico dit "Voiture détectée !"
Route::post('/device/{id}/update', [IoTController::class, 'updateStatus']);


// --- ZONE PROTÉGÉE (Nécessite d'être connecté avec MetaMask) ---
Route::middleware('auth:sanctum')->group(function () {
    
    // Profil Utilisateur
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/user/update', [AuthController::class, 'updateProfile']);

    // Réservation (Le cœur du système)
    Route::post('/reservations', [ReservationController::class, 'store']); // Créer une résa
    Route::get('/reservations/history', [ReservationController::class, 'myHistory']); // Voir historique

});

?>
