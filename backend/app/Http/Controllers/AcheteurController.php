<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAcheteurRequest;
use App\Http\Requests\UpdateAcheteurRequest;
use App\Http\Resources\AcheteurResource;
use App\Models\Acheteur;
use Illuminate\Support\Facades\Gate;

/**
 * Contrôleur de gestion des acheteurs.
 *
 * Gère les opérations CRUD sur les acheteurs enregistrés pour les ventes groupées.
 */
class AcheteurController extends Controller
{
    /**
     * Afficher la liste paginée des acheteurs.
     *
     * Vérifie l'autorisation via Gate::authorize('viewAny', Acheteur::class).
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        Gate::authorize('viewAny', Acheteur::class);

        $acheteurs = Acheteur::orderBy('nom')->paginate(10);

        return AcheteurResource::collection($acheteurs);
    }

    /**
     * Créer un nouvel acheteur.
     *
     * Vérifie l'autorisation via Gate::authorize('create', Acheteur::class).
     *
     * @param  \App\Http\Requests\StoreAcheteurRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreAcheteurRequest $request)
    {
        Gate::authorize('create', Acheteur::class);

        $acheteur = Acheteur::create($request->validated());

        return (new AcheteurResource($acheteur))->response()->setStatusCode(201);
    }

    /**
     * Afficher un acheteur.
     *
     * Vérifie l'autorisation via Gate::authorize('view', $acheteur).
     *
     * @param  \App\Models\Acheteur  $acheteur
     * @return \App\Http\Resources\AcheteurResource
     */
    public function show(Acheteur $acheteur)
    {
        Gate::authorize('view', $acheteur);

        return new AcheteurResource($acheteur);
    }

    /**
     * Modifier un acheteur.
     *
     * Vérifie l'autorisation via Gate::authorize('update', $acheteur).
     *
     * @param  \App\Http\Requests\UpdateAcheteurRequest  $request
     * @param  \App\Models\Acheteur  $acheteur
     * @return \App\Http\Resources\AcheteurResource
     */
    public function update(UpdateAcheteurRequest $request, Acheteur $acheteur)
    {
        Gate::authorize('update', $acheteur);

        $acheteur->update($request->validated());

        return new AcheteurResource($acheteur);
    }

    /**
     * Supprimer un acheteur.
     *
     * Vérifie l'autorisation via Gate::authorize('delete', $acheteur).
     * Retourne une réponse 409 si l'acheteur possède des ventes groupées.
     *
     * @param  \App\Models\Acheteur  $acheteur
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Acheteur $acheteur)
    {
        Gate::authorize('delete', $acheteur);

        // Empêcher la suppression si l'acheteur a des ventes.
        if ($acheteur->ventesGroupees()->exists()) {
            return response()->json([
                'message' => 'Suppression impossible : cet acheteur a des ventes.',
            ], 409);
        }

        $acheteur->delete();

        return response()->json(['message' => 'Acheteur supprimé.']);
    }
}
