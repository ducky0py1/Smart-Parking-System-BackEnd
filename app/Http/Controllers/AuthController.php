<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Fonction pour se connecter avec MetaMask
    public function loginWithWallet(Request $request)
    {
        // 1. On valide qu'on reçoit bien une adresse
        $request->validate([
            'wallet_address' => 'required|string',
        ]);

        // 2. On cherche le user, ou on le crée s'il n'existe pas
        $user = User::firstOrCreate(
            ['wallet_address' => $request->wallet_address]
        );

        // 3. On crée un token API (Sanctum) pour lui
        $token = $user->createToken('auth_token')->plainTextToken;

        // 4. On renvoie le token et les infos du user au Frontend
        return response()->json([
            'message' => 'Connexion réussie',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }
    
    // Fonction pour compléter le profil plus tard
    public function updateProfile(Request $request) {
        $user = auth()->user(); // Récupère l'utilisateur connecté via le token
        
        $request->validate([
            'first_name' => 'nullable|string',
            'email' => 'nullable|email|unique:users,email,'.$user->id
        ]);

        $user->update($request->only(['first_name', 'last_name', 'email']));

        return response()->json(['message' => 'Profil mis à jour', 'user' => $user]);
    }
}






?>