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
        try {
            $userProprio = Auth::user();
            if (!$userProprio || $userProprio->role !== 'proprietaire') {
                return response()->json([
                    'message' => 'Accès refusé. Seuls les propriétaires peuvent voir leurs locations.',
                ], 403);
            }
            
            // Récupérer les locations avec les relations locataire et propriété
            $locations = Location::where('id_proprietaire', $userProprio->proprietaire->id)->with(['locataire.utilisateur', 'propriete'])
                ->paginate(5);
    
            // Reformater les données pour inclure uniquement les champs nécessaires
            $formattedLocations = [];
            foreach ($locations->items() as $location) {
                $formattedLocations[] = [
                    'id' => $location->id,
                    'date' => $location->date,
                    'description_propriete' => $location->propriete->description ?? 'N/A',
                    'nom_locataire' => $location->locataire->utilisateur->nom ?? 'N/A',
                    'loyer_montant' => $location->loyer_montant,
                    'devise' => $location->devise,
                    'confirm' => $location->confirm,
                    'created_at' => $location->created_at->format('d-m-Y'),
                ];
            }
    
            // Retourner une réponse JSON formatée avec la pagination
            return response()->json([
                'message' => 'Liste des locations récupérée avec succès.',
                'locations' => [
                    'data' => $formattedLocations,
                    'pagination' => [
                        'current_page' => $locations->currentPage(),
                        'last_page' => $locations->lastPage(),
                        'per_page' => $locations->perPage(),
                        'total' => $locations->total(),
                    ],
                ],
            ], 200);
    
        } catch (\Exception $e) {
            // Gestion des erreurs
            return response()->json([
                'message' => 'Une erreur est survenue lors de la récupération des locations.',
                'error' => $e->getMessage(),
            ], 500);
        }
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
    public function showlocation()
    {
        //
        try {
            // Vérifier que l'utilisateur est connecté
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'message' => 'Veuillez vous connecter !',
                ], 403);
            }
        
            // Récupère les informations de la location
            $location=$user->locataire->location;
            if (!$location) {
                return response()->json([
                    'message' => 'Aucune location trouvée.',
                ], 404);
            }    
            // Reformater les données pour inclure uniquement les champs nécessaires
            $formattedLocation = [
                'id' => $location->id,
                'date' => $location->date,
                'description' => $location->description,
                'loyer_montant' => $location->loyer_montant,
                'devise' => $location->devise,
                'confirm' => $location->confirm,
                'propriete' => [
                    'id' => $location->propriete->id ?? null,
                    'adresse' => $location->propriete->adresse ?? 'N/A',
                    'description' => $location->propriete->description ?? 'N/A',
                ],
                'locataire' => [
                    'nom' => $location->locataire->utilisateur->nom ?? 'N/A',
                    'tel' => $location->locataire->utilisateur->tel ?? 'N/A',
                ],
                'proprietaire' => [
                    'nom' => $location->proprietaire->utilisateur->nom ?? 'N/A',
                    'tel' => $location->proprietaire->utilisateur->tel ?? 'N/A',
                ],
                'created_at' => $location->created_at->format('d-m-Y'),
            ];
    
            // Retourner la réponse JSON
            return response()->json([
                'message' => 'Détails de la location récupérés avec succès.',
                'location' => $formattedLocation,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la récupération des détails de la location.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
       
        try {
            $location = Location::findOrFail($id);
            // Vérifier que l'utilisateur est connecté
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'message' => 'Veuillez vous connecter !',
                ], 403);
            }
            
            // Vérifier si l'utilisateur est propriétaire ou locataire de la location
            if (
                (($user->role === 'proprietaire') && ($location->id_proprietaire !== $user->proprietaire->id)) ||
                ($user->role === 'locataire' && $location->id_locataire !== $user->locataire->id)
            ) {
                return response()->json([
                    'message' => 'Accès refusé : cette location ne vous est pas associée.',
                ], 403);
            }
    
            // Charger les relations nécessaires
            $location->load(['locataire.utilisateur', 'propriete', 'proprietaire.utilisateur']);
    
            // Reformater les données pour inclure uniquement les champs nécessaires
            $formattedLocation = [
                'id' => $location->id,
                'date' => $location->date,
                'description' => $location->description,
                'loyer_montant' => $location->loyer_montant,
                'devise' => $location->devise,
                'confirm' => $location->confirm,
                'propriete' => [
                    'id' => $location->propriete->id ?? null,
                    'adresse' => $location->propriete->adresse ?? 'N/A',
                    'description' => $location->propriete->description ?? 'N/A',
                ],
                'locataire' => [
                    'nom' => $location->locataire->utilisateur->nom ?? 'N/A',
                    'tel' => $location->locataire->utilisateur->tel ?? 'N/A',
                ],
                'proprietaire' => [
                    'nom' => $location->proprietaire->utilisateur->nom ?? 'N/A',
                    'tel' => $location->proprietaire->utilisateur->tel ?? 'N/A',
                ],
                'created_at' => $location->created_at->format('d-m-Y'),
            ];
    
            // Retourner la réponse JSON
            return response()->json([
                'message' => 'Détails de la location récupérés avec succès.',
                'location' => $formattedLocation,
            ], 200);
    
        } catch (\Exception $e) {
            // Gestion des erreurs (par exemple, location non trouvée)
            return response()->json([
                'message' => 'Une erreur est survenue lors de la récupération des détails de la location.',
                'error' => $e->getMessage(),
            ], 500);
        }
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
