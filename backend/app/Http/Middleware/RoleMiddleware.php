<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware de contrôle d'accès basé sur le rôle de l'utilisateur.
 *
 * Intercepte les requêtes avant qu'elles n'atteignent le contrôleur et
 * n'autorise que les utilisateurs dont le rôle figure dans la liste
 * transmise au middleware.
 */
class RoleMiddleware
{
    /**
     * Autoriser la requête seulement si le rôle de l'utilisateur
     * fait partie des rôles passés en paramètre.
     * Exemple : role:admin  ou  role:admin,gestionnaire
     *
     * Si aucun utilisateur n'est authentifié ou si son rôle n'est pas
     * autorisé, une réponse JSON 403 « Accès interdit. » est renvoyée
     * sans exécuter la suite de la chaîne de traitement.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @param  string  ...$roles
     * @return Response
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