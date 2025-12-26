<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // 1. Connexion / Inscription via MetaMask
    public function loginWithWallet(Request $request)
    {
        // On valide qu'on reçoit bien une adresse
        $validator = Validator::make($request->all(), [
            'wallet_address' => 'required|string|size:42', // Une adresse ETH fait 42 chars
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Adresse invalide'], 422);
        }

        // Magie Laravel : Cherche l'user, ou le crée s'il n'existe pas
        $user = User::firstOrCreate(
            ['wallet_address' => $request->wallet_address]
        );

        // On crée un Token de sécurité (Sanctum) pour que React puisse faire des requêtes après
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Connexion réussie',
            'token' => $token,
            'user' => $user
        ], 200);
    }

    // 2. Compléter son profil (Nom, Email...)
    public function updateProfile(Request $request)
    {
        // L'utilisateur est déjà identifié grâce au Token Sanctum (middleware)
        $user = $request->user(); 

        // Validation des entrées
        $request->validate([
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'email' => 'nullable|email|unique:users,email,' . $user->id, // Unique sauf pour lui-même
        ]);

        // Mise à jour
        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
        ]);

        return response()->json([
            'message' => 'Profil mis à jour avec succès',
            'user' => $user
        ]);
    }
}

?>