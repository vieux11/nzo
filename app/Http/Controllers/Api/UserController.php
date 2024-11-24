<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LogUserRequest;
use App\Http\Requests\RegisterUser;
use App\Models\Locataire;
use App\Models\Proprietaire;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function register(RegisterUser $request)
    {

        try {
                 // Création de l'utilisateur  
        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'tel' => $request->tel,
            'role' => 'proprietaire',
            'email' => $request->email,
            'password' => Hash::make($request->password, ['round'=>12])
        ]);
            Proprietaire::create([
                'user_id' => $user->id, // liaison avec l'utilisateur
            ]);
        // Retourner une réponse JSON avec les détails de l'utilisateur créé
        return response()->json([
            'message' => 'Utilisateur enregistré avec succès',
            'user' => $user,
        ], 201);
            
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
    public function login(LogUserRequest $request)
    {
        // Récupérer les données validées
        
        $credentials = $request->validated();
        // Recherche de l'utilisateur correspondant à l'identifiant
        $user = User::where('email', $credentials['identifiant'])
                    ->orWhere('tel', $credentials['identifiant'])
                    ->first();
        // Vérification : Utilisateur existe + mot de passe correct ?
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            // Soit l'utilisateur n'existe pas, soit le mot de passe ne correspond pas
            return response()->json([
                'success' => false,
                'message' => 'Identifiant ou mot de passe incorrect.',
            ], 401);
        }
        // Si on arrive ici, l'utilisateur est validé : Créons un token
        $token = $user->createToken('NZOAPP_auth_token_ETHY_BMN')->plainTextToken;

        // Réponse en cas de succès
        return response()->json([
            'success' => true,
            'message' => 'Connexion réussie.',
            'user' => [
                'id' => $user->id,
                'nom' => $user->nom,
                'prenom' => $user->prenom,
                'tel' => $user->tel,
                'role' => $user->role,
                'email' => $user->email,
            ],
            'token' => $token,
        ], 200);
    }
    public function logout(Request $request)
    {
        // Révoquer le token en cours
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Déconnecté avec succès.',
        ]);
    }

    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
