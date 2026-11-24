<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDepotStockRequest;
use App\Http\Requests\UpdateDepotStockRequest;
use App\Http\Resources\DepotStockResource;
use App\Models\DepotStock;
use Illuminate\Support\Facades\Gate;

/**
 * Contrôleur de gestion des dépôts de stock.
 *
 * Gère les opérations CRUD sur les dépôts de stock effectués par les membres.
 */
class DepotStockController extends Controller
{
    /**
     * Afficher la liste paginée des dépôts de stock.
     *
     * Vérifie l'autorisation via Gate::authorize('viewAny', DepotStock::class).
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        Gate::authorize('viewAny', DepotStock::class);

        $depotsStock = DepotStock::with(['membre', 'produit'])
            ->orderBy('date_depot', 'desc')
            ->paginate(10);

        return DepotStockResource::collection($depotsStock);
    }

    /**
     * Créer un dépôt de stock.
     *
     * Vérifie l'autorisation via Gate::authorize('create', DepotStock::class).
     *
     * @param  \App\Http\Requests\StoreDepotStockRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreDepotStockRequest $request)
    {
        Gate::authorize('create', DepotStock::class);

        $depotStock = DepotStock::create($request->validated());
        $depotStock->load(['membre', 'produit']);

        return (new DepotStockResource($depotStock))->response()->setStatusCode(201);
    }

    /**
     * Afficher un dépôt de stock.
     *
     * Vérifie l'autorisation via Gate::authorize('view', $depotsStock).
     *
     * @param  \App\Models\DepotStock  $depotsStock
     * @return \App\Http\Resources\DepotStockResource
     */
    public function show(DepotStock $depotsStock)
    {
        Gate::authorize('view', $depotsStock);

        $depotsStock->load(['membre', 'produit']);

        return new DepotStockResource($depotsStock);
    }

    /**
     * Modifier un dépôt de stock.
     *
     * Vérifie l'autorisation via Gate::authorize('update', $depotsStock).
     *
     * @param  \App\Http\Requests\UpdateDepotStockRequest  $request
     * @param  \App\Models\DepotStock  $depotsStock
     * @return \App\Http\Resources\DepotStockResource
     */
    public function update(UpdateDepotStockRequest $request, DepotStock $depotsStock)
    {
        Gate::authorize('update', $depotsStock);

        $depotsStock->update($request->validated());
        $depotsStock->load(['membre', 'produit']);

        return new DepotStockResource($depotsStock);
    }

    /**
     * Supprimer un dépôt de stock.
     *
     * Vérifie l'autorisation via Gate::authorize('delete', $depotsStock).
     *
     * @param  \App\Models\DepotStock  $depotsStock
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DepotStock $depotsStock)
    {
        Gate::authorize('delete', $depotsStock);

        $depotsStock->delete();

        return response()->json(['message' => 'Dépôt supprimé.']);
    }
}
