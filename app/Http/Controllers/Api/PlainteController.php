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
}
