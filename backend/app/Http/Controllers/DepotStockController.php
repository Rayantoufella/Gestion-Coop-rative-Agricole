<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDepotStockRequest;
use App\Http\Requests\UpdateDepotStockRequest;
use App\Http\Resources\DepotStockResource;
use App\Models\DepotStock;
use Illuminate\Support\Facades\Gate;

class DepotStockController extends Controller
{
    /**
     * Afficher la liste des dépôts de stock.
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
     */
    public function show(DepotStock $depotsStock)
    {
        Gate::authorize('view', $depotsStock);

        $depotsStock->load(['membre', 'produit']);

        return new DepotStockResource($depotsStock);
    }

    /**
     * Modifier un dépôt de stock.
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
     */
    public function destroy(DepotStock $depotsStock)
    {
        Gate::authorize('delete', $depotsStock);

        $depotsStock->delete();

        return response()->json(['message' => 'Dépôt supprimé.']);
    }
}
