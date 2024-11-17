<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group (function(){
    Route::get('/user', function (Request $request) {
    return $request->user();
    });
    Route::post('/user/logout', [UserController::class, 'logout']);
});
Route::post('/user/register', [UserController::class, 'register']);
Route::post('user/login', [UserController::class, 'login']);
