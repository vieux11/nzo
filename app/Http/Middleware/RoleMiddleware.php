<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        
        // Vérifier que l'utilisateur est authentifié et que son rôle correspond
        if (Auth::check() && Auth::user()->role === $role) {
            return $next($request); // Autorise la requête
        }
        //Si l'utilisateur n'a pas le bon rôle, retourner une erreur
        return response()->json(['message' => 'Accès refusé.'], 403);
    }
}
