<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCotisationRequest;
use App\Http\Requests\UpdateCotisationRequest;
use App\Http\Resources\CotisationResource;
use App\Models\Cotisation;
use Illuminate\Support\Facades\Gate;

/**
 * Contrôleur de gestion des cotisations.
 *
 * Gère les opérations CRUD sur les cotisations versées par les membres.
 */
class CotisationController extends Controller
{
    /**
     * Afficher la liste paginée des cotisations.
     *
     * Les cotisations sont triées par date de paiement décroissante.
     * Vérifie l'autorisation via Gate::authorize('viewAny', Cotisation::class).
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        //
        Gate::authorize('viewAny', Cotisation::class);

        $cotisations = Cotisation::with('membre')->orderBy('date_paiement', 'desc')->paginate(10);

        return CotisationResource::collection($cotisations);
    }

    /**
     * Enregistrer une nouvelle cotisation.
     *
     * Vérifie l'autorisation via Gate::authorize('create', Cotisation::class).
     *
     * @param  \App\Http\Requests\StoreCotisationRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCotisationRequest $request)
    {
        //
        Gate::authorize('create', Cotisation::class);

        $cotisation = Cotisation::create($request->validated());
        $cotisation->load('membre');

        return (new CotisationResource($cotisation))->response()->setStatusCode(201);
    }

    /**
     * Afficher une cotisation.
     *
     * Vérifie l'autorisation via Gate::authorize('view', $cotisation).
     *
     * @param  \App\Models\Cotisation  $cotisation
     * @return \App\Http\Resources\CotisationResource
     */
    public function show(Cotisation $cotisation)
    {
        //
        Gate::authorize('view', $cotisation);

        $cotisation->load('membre');

        return new CotisationResource($cotisation);
    }

    /**
     * Modifier une cotisation.
     *
     * Vérifie l'autorisation via Gate::authorize('update', $cotisation).
     *
     * @param  \App\Http\Requests\UpdateCotisationRequest  $request
     * @param  \App\Models\Cotisation  $cotisation
     * @return \App\Http\Resources\CotisationResource
     */
    public function update(UpdateCotisationRequest $request, Cotisation $cotisation)
    {
        //
        Gate::authorize('update', $cotisation);

        $cotisation->update($request->validated());
        $cotisation->load('membre');

        return new CotisationResource($cotisation);
    }

    /**
     * Supprimer une cotisation.
     *
     * Vérifie l'autorisation via Gate::authorize('delete', $cotisation).
     *
     * @param  \App\Models\Cotisation  $cotisation
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Cotisation $cotisation)
    {
        //
        Gate::authorize('delete', $cotisation);

        $cotisation->delete();

        return response()->json(['message' => 'Cotisation supprimée avec succès.'], 200);
    }
}
