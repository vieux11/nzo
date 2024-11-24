<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProprieteRequest;
use App\Models\Propriete;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\TryCatch;

class ProprieteController extends Controller
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
    public function create(StoreProprieteRequest $request)
    {
        try {
            $userProprio = Auth::user();
            if (!$userProprio || $userProprio->role !== 'proprietaire') {
                return response()->json([
                    'message' => 'Accès refusé. Seuls les propriétaires peuvent créer des locataires.',
                ], 403);
            }
            // Valider et créer la propriété
            $validatedata= $request->validated();
            $propriete = Propriete::create([
                'description' => $validatedata['description'],
                'adresse' => $validatedata['adresse'],
                'id_proprietaire'=> $userProprio->proprietaire->id
                ]);
            // Retourner une réponse JSON de succès
            return response()->json([
                'message' => 'Propriété créée avec succès.',
                'propriete' => $propriete,
            ], 201);
        } catch (Exception $e) {
            return response()->json($e);
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
    public function show(Propriete $propriete)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Propriete $propriete)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Propriete $propriete)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Propriete $propriete)
    {
        //
    }
}
