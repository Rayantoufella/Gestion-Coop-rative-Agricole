<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVenteGroupeeRequest;
use App\Http\Resources\VenteGroupeeResource;
use App\Models\DepotStock;
use App\Models\VenteGroupee;
use Illuminate\Support\Facades\Gate;

class VenteGroupeeController extends Controller
{
    /**
     * Afficher la liste des ventes groupées.
     */
    public function index()
    {
        Gate::authorize('viewAny', VenteGroupee::class);

        $ventesGroupees = VenteGroupee::with(['produit', 'acheteur'])
            ->orderBy('date_vente', 'desc')
            ->paginate(10);

        return VenteGroupeeResource::collection($ventesGroupees);
    }

    /**
     * Créer une vente groupée.
     */
    public function store(StoreVenteGroupeeRequest $request)
    {
        Gate::authorize('create', VenteGroupee::class);

        // Calculer le stock disponible du produit.
        $totalDepots = DepotStock::where('produit_id', $request->produit_id)->sum('quantite');
        $totalVentes = VenteGroupee::where('produit_id', $request->produit_id)->sum('quantite');
        $stockDisponible = $totalDepots - $totalVentes;

        // Refuser la vente si le stock est insuffisant.
        if ($request->quantite > $stockDisponible) {
            return response()->json([
                'message' => 'Stock insuffisant.',
                'stock_disponible' => $stockDisponible,
            ], 422);
        }

        $venteGroupee = VenteGroupee::create($request->validated());
        $venteGroupee->load(['produit', 'acheteur']);

        return (new VenteGroupeeResource($venteGroupee))->response()->setStatusCode(201);
    }

    /**
     * Afficher une vente groupée.
     */
    public function show(VenteGroupee $venteGroupee)
    {
        Gate::authorize('view', $venteGroupee);

        $venteGroupee->load(['produit', 'acheteur']);

        return new VenteGroupeeResource($venteGroupee);
    }

    /**
     * Supprimer une vente groupée.
     */
    public function destroy(VenteGroupee $venteGroupee)
    {
        Gate::authorize('delete', $venteGroupee);

        $venteGroupee->delete();

        return response()->json(['message' => 'Vente supprimée.']);
    }
}
