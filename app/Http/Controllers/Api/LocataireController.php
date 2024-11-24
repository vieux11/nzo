<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUser;
use App\Models\Locataire;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LocataireController extends Controller
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
    public function create(RegisterUser $request)
    {
        //
        try {
            
            $userProprio = Auth::user();
            if (!$userProprio || $userProprio->role !== 'proprietaire') {
                return response()->json([
                    'message' => 'Accès refusé. Seuls les propriétaires peuvent créer des locataires.',
                ], 403);
            }
            // Création de l'utilisateur locataire 
            $user = User::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'tel' => $request->tel,
                'role' => 'locataire',
                'email' => $request->email,
                'password' => Hash::make($request->password, ['round'=>12])
            ]);
            Locataire::create([
            'user_id' => $user->id, // liaison avec l'utilisateur
            'id_proprietaire'=> $userProprio->proprietaire->id,
            ]);
        // Retourner une réponse JSON avec les détails de l'utilisateur créé
            return response()->json([
            'message' => 'Utilisateur enregistré avec succès',
            'user' => $user,
            ], 201);
       
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la création du locataire.',
                'error' => $e->getMessage(),
            ], 500);
        }
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Locataire $locataire)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Locataire $locataire)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Locataire $locataire)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Locataire $locataire)
    {
        //
    }
}
