<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlainteRequest;
use App\Models\Plainte;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlainteController extends Controller
{
    //
    public function getPlaintes()
    {
        try {
            // Vérifier que l'utilisateur est connecté et est un propriétaire
            $user = Auth::user();
            if (!$user || $user->role !== 'proprietaire') {
                return response()->json([
                    'message' => 'Accès refusé : seuls les propriétaires peuvent accéder aux plaintes.',
                ], 403);
            }

            // Récupérer les plaintes associées au propriétaire
            $plaintes = $user->proprietaire->plaintes;
            //dd($plaintes);
            $formattedPlaintes = $plaintes->map(function ($plainte) {
                return [
                    'id' => $plainte->id,
                    'sujet' => $plainte->sujet,
                    'description' => $plainte->description,
                    'nom_locataire' => optional($plainte->locataire->utilisateur)->nom ?? 'N/A',
                ];
            })->toArray();
            return response()->json([
                'message' => 'Plaintes récupérées avec succès.',
                'plaintes' => $formattedPlaintes,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la récupération des plaintes.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(StorePlainteRequest $request)
    {    
        try 
        {
            $userlocataire = Auth::user();
    
            if (!$userlocataire || $userlocataire->role !== 'locataire') {
                return response()->json([
                    'message' => 'Accès refusé. Seuls les locataires peuvent déposer des plaintes.',
                ], 403);
            }
    
            $plainte = Plainte::create([
                'sujet' => $request->sujet,
                'description' => $request->description,
                'status' => 'en_cours',
                'id_locataire' => $userlocataire->locataire->id,
                'id_proprietaire' => $userlocataire->locataire->id_proprietaire,
            ]);
            $plainte->date_plainte = Carbon::parse($plainte->date_plainte)->format('d-m-Y');
            return response()->json([
                'message' => 'Plainte enregistrée avec succès.',
                'plainte' => $plainte,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de l\'enregistrement de la plainte.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function updateStatus(Request $request, $id){
        $user = Auth::user();

        if ($user->role !== 'locataire') {
            return response()->json(['message' => 'Accès refusé.'], 403);
        }

        $plainte = Plainte::findOrFail($id);

        if ($plainte->id_locataire !== $user->locataire->id) {
            return response()->json(['message' => 'Cette plainte ne vous appartient pas.'], 403);
        }

        $validated = $request->validate(['status' => 'required|in:résolue,fermée'],
        [
            'status.required' => 'Le statut est requis.',
            'status.in' => 'Le statut doit être soit "résolue" soit "fermée".',
        ]);
        // Mise à jour du statut du paiement
        $plainte->status = $validated['status'];
        $plainte->save();
        return response()->json(['message' => 'Statut mis à jour avec succès.'], 200);
    }
}
