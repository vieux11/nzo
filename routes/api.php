<?php

use App\Http\Controllers\Api\LocataireController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\payementController;
use App\Http\Controllers\Api\PlainteController;
use App\Http\Controllers\Api\ProprieteController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/user/register', [UserController::class, 'register']);
Route::post('user/login', [UserController::class, 'login']);
Route::middleware('auth:sanctum')->group (function (){
    Route::get('/user', function (Request $request) {
    return $request->user();
    });
    Route::post('/user/logout', [UserController::class, 'logout']);
});
Route::middleware(['auth:sanctum'])->group(function () {
    // Routes pour les propriétaires
    Route::middleware(RoleMiddleware::class.':proprietaire')->group(function () {
        Route::get('/user/redigerContrat', function(){
            return 'contrat rédigé';
        });
        Route::post('user/createLocataire', [LocataireController::class, 'create']);
        Route::post('user/createPropriete', [ProprieteController::class, 'create']);
        Route::post('user/createLocation', [LocationController::class, 'create']);
        Route::patch('user/payements/{paiement_id}/status', [PayementController::class, 'updateStatus']);
        Route::get('user/locations', [LocationController::class, 'index']);
        Route::get('user/locations/{location_id}', [LocationController::class, 'show']);

        // Ajoutez ici d'autres routes spécifiques aux propriétaires
    });
    // Routes pour les locataires
    Route::middleware(RoleMiddleware::class.':locataire')->group(function () {
        Route::get('/user/validatecontract', function(){
            return 'contrat validé';
        });
        Route::post('user/createPlainte', [PlainteController::class, 'store']);
        Route::patch('user/plaintes/{plainte_id}/status', [PlainteController::class, 'updateStatus']);
        Route::post('user/payement', [payementController::class, 'payement']);
        Route::get('user/location', [LocationController::class, 'showlocation']);
        // Ajoutez ici d'autres routes spécifiques aux locataires
    });
});
