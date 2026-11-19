<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUtilisateurRequest;
use App\Http\Requests\UpdateUtilisateurRequest;
use App\Http\Resources\UtilisateurResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

/**
 * Contrôleur de gestion des comptes utilisateurs (paramètres de l'admin).
 *
 * Permet à l'administrateur de voir les comptes (avec leur dernière
 * activité), de créer, modifier et supprimer un compte gestionnaire.
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
     * @return AnonymousResourceCollection
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
     * @return JsonResponse
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

    /**
     * Afficher un compte gestionnaire.
     *
     * Vérifie l'autorisation via Gate::authorize('view', $utilisateur).
     *
     * @return UtilisateurResource
     */
    public function show(User $utilisateur)
    {
        Gate::authorize('view', $utilisateur);

        return new UtilisateurResource($utilisateur);
    }

    /**
     * Modifier un compte gestionnaire.
     *
     * Vérifie l'autorisation via Gate::authorize('update', $utilisateur).
     * Le rôle n'est jamais modifié. Le mot de passe est facultatif : s'il
     * est renseigné, il est haché automatiquement par le cast du modèle et
     * les sessions existantes du gestionnaire sont révoquées.
     *
     * @return UtilisateurResource
     */
    public function update(UpdateUtilisateurRequest $request, User $utilisateur)
    {
        Gate::authorize('update', $utilisateur);

        $donnees = $request->validated();
        $motDePasseModifie = ! empty($donnees['password']);

        $utilisateur->name = $donnees['name'];
        $utilisateur->email = $donnees['email'];

        if ($motDePasseModifie) {
            $utilisateur->password = $donnees['password'];
        }

        $utilisateur->save();

        if ($motDePasseModifie) {
            $utilisateur->tokens()->delete();
        }

        return new UtilisateurResource($utilisateur);
    }

    /**
     * Supprimer un compte gestionnaire.
     *
     * Vérifie l'autorisation via Gate::authorize('delete', $utilisateur).
     * Les jetons Sanctum du compte sont supprimés avant le compte afin de
     * révoquer immédiatement ses sessions actives.
     *
     * @return JsonResponse
     */
    public function destroy(User $utilisateur)
    {
        Gate::authorize('delete', $utilisateur);

        $utilisateur->tokens()->delete();
        $utilisateur->delete();

        return response()->json(['message' => 'Compte supprimé.']);
    }
}
