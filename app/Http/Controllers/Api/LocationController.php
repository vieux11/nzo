<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLocationRequest;
use App\Models\Location;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
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
    
    public function create(StoreLocationRequest $request)
    {
        // Crée une nouvelle location avec les données validées
        // Formatage de la date avant retour
        try {
            $userProprio = Auth::user();
            if (!$userProprio || $userProprio->role !== 'proprietaire') {
                return response()->json([
                    'message' => 'Accès refusé. Seuls les propriétaires peuvent créer des locataires.',
                ], 403);
            }
        //valider les informations
        $validatedata=$request->validated();
        $location = Location::create([
            'id_proprietaire' => $userProprio->proprietaire->id,
            'description' => $validatedata['description'],
            'loyer_montant' => $validatedata['loyer_montant'],
            'id_propriete' => $validatedata['id_propriete'],
            'id_locataire' => $validatedata['id_locataire'],
            'devise' => $validatedata['devise']
        ]);
        $location->date = Carbon::parse($location->date)->format('d-m-Y');

        // Retourne les données formatées dans une réponse JSON
        return response()->json([
            'id' => $location->id,
            'date' => $location->date, // Formate la date au format JJ-MM-AAAA
            'description' => $location->description,
            'loyer_montant' => $location->loyer_montant,
            'confirm' => $location->confirm,
            'propriete' => [
                'id' => $location->propriete->id ?? null,
                'adresse' => $location->propriete->adresse ?? null,
            ],
            'locataire' => [
                'id' => $location->id_locataire ?? null,
                'nom' => $location->locataire->utilisateur->nom ?? null,
            ],
            'proprio' => [
                'id' => $location->proprietaire->id ?? null,
                'nom' => $location->proprietaire->utilisateur->nom ?? null,
            ],
        ], 201); // Code 201 : Ressource créée avec succès
        } catch (Exception $e) {
            return response()->json($e);
        }
        
    }
    /**
     * Crée une nouvelle location.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Location $location)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Location $location)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Location $location)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        //
    }
}
