<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Autoriser la requête seulement si le rôle de l'utilisateur
     * fait partie des rôles passés en paramètre.
     * Exemple : role:admin  ou  role:admin,gestionnaire
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        // Pas d'utilisateur, ou rôle non autorisé : 403
        if (! $user || ! in_array($user->role, $roles)) {
            return response()->json([
                'message' => 'Accès interdit.',
            ], 403);
        }

        return $next($request);
    }
}