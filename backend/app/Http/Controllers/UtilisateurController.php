<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUtilisateurRequest;
use App\Http\Resources\UtilisateurResource;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

/**
 * Contrôleur de gestion des comptes utilisateurs (paramètres de l'admin).
 *
 * Permet à l'administrateur de voir les comptes (avec leur dernière
 * activité) et de créer un compte gestionnaire.
 */
class UtilisateurController extends Controller
{
    /**
     * Afficher la liste paginée des comptes.
     *
     * Vérifie l'autorisation via Gate::authorize('viewAny', User::class).
     * withMax('tokens', 'last_used_at') ajoute la date de la dernière
     * utilisation d'un jeton de connexion (champ tokens_max_last_used_at).
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        Gate::authorize('viewAny', User::class);

        $utilisateurs = User::withMax('tokens', 'last_used_at')
            ->orderBy('role')
            ->orderBy('name')
            ->paginate(10);

        return UtilisateurResource::collection($utilisateurs);
    }

    /**
     * Créer un nouveau compte gestionnaire.
     *
     * Vérifie l'autorisation via Gate::authorize('create', User::class).
     * Le rôle est toujours « gestionnaire » : l'admin ne peut pas créer
     * un autre admin depuis l'application. Le mot de passe est haché
     * automatiquement grâce au cast « hashed » du modèle User.
     *
     * @param  \App\Http\Requests\StoreUtilisateurRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUtilisateurRequest $request)
    {
        Gate::authorize('create', User::class);

        $donnees = $request->validated();

        $utilisateur = User::create([
            'name' => $donnees['name'],
            'email' => $donnees['email'],
            'password' => $donnees['password'],
            'role' => 'gestionnaire',
        ]);

        return (new UtilisateurResource($utilisateur))->response()->setStatusCode(201);
    }
}
