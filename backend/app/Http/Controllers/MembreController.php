<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMembreRequest;
use App\Http\Requests\UpdateMembreRequest;
use App\Http\Resources\MembreResource;
use App\Models\Membre;
use Illuminate\Support\Facades\Gate;

/**
 * Contrôleur de gestion des membres.
 *
 * Gère les opérations CRUD sur les membres de l'organisation.
 */
class MembreController extends Controller
{
    /**
     * Afficher la liste paginée des membres.
     *
     * Vérifie l'autorisation via Gate::authorize('viewAny', Membre::class).
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        //
        Gate::authorize('viewAny', Membre::class);

        return MembreResource::collection(Membre::orderBy('nom')->paginate(10));
    }

    /**
     * Enregistrer un nouveau membre.
     *
     * Vérifie l'autorisation via Gate::authorize('create', Membre::class).
     *
     * @param  \App\Http\Requests\StoreMembreRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreMembreRequest $request)
    {
        //
        Gate::authorize('create', Membre::class);
        $membre = Membre::create($request->validated());

        return (new MembreResource($membre))->response()->setStatusCode(201);
    }

    /**
     * Afficher un membre.
     *
     * Vérifie l'autorisation via Gate::authorize('view', $membre).
     *
     * @param  \App\Models\Membre  $membre
     * @return \App\Http\Resources\MembreResource
     */
    public function show(Membre $membre)
    {
        //
        Gate::authorize('view', $membre);

        return new MembreResource($membre);
    }

    /**
     * Modifier un membre.
     *
     * Vérifie l'autorisation via Gate::authorize('update', $membre).
     *
     * @param  \App\Http\Requests\UpdateMembreRequest  $request
     * @param  \App\Models\Membre  $membre
     * @return \App\Http\Resources\MembreResource
     */
    public function update(UpdateMembreRequest $request, Membre $membre)
    {
        //
        Gate::authorize('update', $membre);
        $membre->update($request->validated());

        return new MembreResource($membre);

    }

    /**
     * Supprimer un membre.
     *
     * Vérifie l'autorisation via Gate::authorize('delete', $membre).
     * Retourne une réponse 400 si le membre possède des cotisations ou des dépôts de stock.
     *
     * @param  \App\Models\Membre  $membre
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Membre $membre)
    {
        //
        Gate::authorize('delete', $membre);
        if ($membre->cotisations()->exists() || $membre->depotsStock()->exists()) {
            return response()->json([
                'message' => 'Impossible de supprimer le membre car il a des cotisations ou des dépôts de stock associés.',
            ], 400);
        }
        $membre->delete();

        return response()->json([
            'message' => 'Membre supprimé avec succès.',
        ], 200);
    }
}
