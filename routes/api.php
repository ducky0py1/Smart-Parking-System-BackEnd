<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ParkingSpotController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
// --- Routes Publiques (Tout le monde peut y accéder) ---

// Login avec MetaMask
Route::post('/auth/wallet', [AuthController::class, 'loginWithWallet']);

// Récupérer la liste des places (pour la carte)
Route::get('/spots', [ParkingSpotController::class, 'index']);
Route::get('/spots/{id}', [ParkingSpotController::class, 'show']);


// --- Routes Protégées (Il faut être connecté) ---
Route::middleware('auth:sanctum')->group(function () {
    
    // Exemple : L'utilisateur veut voir son profil
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Mettre à jour son profil (Nom, Email)
    Route::post('/user/update', [AuthController::class, 'updateProfile']);
    
    // (Plus tard, on ajoutera ici la route pour réserver)
});

?>
