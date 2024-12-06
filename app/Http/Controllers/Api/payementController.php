<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaiementRequest;
use App\Models\Payement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class payementController extends Controller
{
    //
    public function payement(PaiementRequest $request) {
        try {
            // Vérification : l'utilisateur doit être un locataire
            $user = Auth::user();
            if (!$user || $user->role !== 'locataire') {
                return response()->json([
                    'message' => 'Seuls les locataires peuvent effectuer des paiements.',
                ], 403);
            }
            // Récupérer la location associée au locataire
            $location = $user->locataire->location;
            if (!$location) {
                return response()->json([
                    'message' => 'Aucune location associée à ce locataire.',
                ], 404);
            }
            // Récupérer le dernier paiement pour déterminer le mois et l'année
            $dernierPaiement = Payement::where('id_location', $location->id)
                ->orderBy('annee', 'desc')
                ->orderBy('mois', 'desc')
                ->first();
            // Déterminer le mois et l'année du paiement
            if ($dernierPaiement) {
                // Vérifier si le statut est `effectué`
                if ($dernierPaiement->status == 'en_cours') {
                    return response()->json([
                        'message' => 'Le dernier paiement doit être validé avant d\'effectuer un nouveau paiement.',
                    ], 400);
                }
                elseif ($dernierPaiement->status == 'rejeté') {
                    $mois = $dernierPaiement->mois;
                    $annee = $dernierPaiement->annee;
                }
                else {
                    $mois = ($dernierPaiement->mois % 12) + 1;
                    $annee = $dernierPaiement->mois === 12 ? $dernierPaiement->annee + 1 : $dernierPaiement->annee;
                }
            } else {
                // Valider que `mois` et `annee` sont fournis
            $request->validate([
                'mois' => 'required|integer|between:1,12',
                'annee' => 'required|integer|min:2000',
            ], [
                'mois.required' => 'Le mois est obligatoire pour un premier paiement.',
                'annee.required' => 'L\'année est obligatoire pour un premier paiement.',
            ]);
                $mois = $request->mois;
                $annee = $request->annee;
            }
            // Déterminer la date du paiement
            if ($request->date_payement){
                $date_payement = $request->date_payement;
            }
            else{
                $date_payement = Carbon::parse(now())->format('Y-m-d');
            }
    
            // Récupérer le montant et la devise depuis la location
            $montant = $location->loyer_montant ?? $request->montant ;
            $devise = $location->devise ?? $request->devise;
            // Vérifier si un paiement validé existe déjà pour ce mois
            $paiementExistant = Payement::where('id_location', $location->id)
            ->where('mois', $mois)
            ->where('annee', $annee)
            ->where('status', 'effectué')
            ->exists();
            if ($paiementExistant) {
                return response()->json([
                    'message' => 'Un paiement validé existe déjà pour ce mois.',
                ], 400);
            }
            // Créer le paiement
            $paiement = Payement::create([
                'montant' => $montant,
                'devise' => $devise,
                'date_payement' => $date_payement,
                'status' => 'en_cours',
                'methode_paiement' => $request->methode_paiement,
                'id_location' => $location->id,
                'id_locataire' => $user->locataire->id,
                'mois' => $mois,
                'annee' => $annee,
            ]);
    
            // Réponse de succès
            return response()->json([
                'message' => 'Paiement enregistré avec succès.',
                'paiement' => $paiement,
            ], 201);
    
        } catch (\Exception $e) {
            // Gestion des erreurs
            return response()->json([
                'message' => 'Une erreur est survenue lors de l\'enregistrement du paiement.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function updateStatus(Request $request, $id){
        $user = Auth::user();

        if ($user->role !== 'proprietaire') {
            return response()->json(['message' => 'Accès refusé.'], 403);
        }

        $paiement = Payement::findOrFail($id);

        if ($paiement->locataire->id_proprietaire !== $user->proprietaire->id) {
            return response()->json(['message' => 'Ce paiement ne vous appartient pas.'], 403);
        }

        $validated = $request->validate(['status' => 'required|in:effectué,rejeté']);
        // Mise à jour du statut du paiement
        $paiement->status = $validated['status'];
        $paiement->save();
        return response()->json(['message' => 'Statut mis à jour avec succès.'], 200);
    }
}
