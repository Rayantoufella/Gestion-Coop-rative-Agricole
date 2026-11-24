<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVenteGroupeeRequest;
use App\Http\Resources\VenteGroupeeResource;
use App\Models\DepotStock;
use App\Models\VenteGroupee;
use Illuminate\Support\Facades\Gate;

/**
 * Contrôleur de gestion des ventes groupées.
 *
 * Gère les opérations sur les ventes groupées de produits aux acheteurs.
 */
class VenteGroupeeController extends Controller
{
    /**
     * Afficher la liste paginée des ventes groupées.
     *
     * Vérifie l'autorisation via Gate::authorize('viewAny', VenteGroupee::class).
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
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
     *
     * Vérifie l'autorisation via Gate::authorize('create', VenteGroupee::class).
     * Retourne une réponse 422 si le stock disponible est insuffisant.
     *
     * @param  \App\Http\Requests\StoreVenteGroupeeRequest  $request
     * @return \Illuminate\Http\JsonResponse
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
     *
     * Vérifie l'autorisation via Gate::authorize('view', $venteGroupee).
     *
     * @param  \App\Models\VenteGroupee  $venteGroupee
     * @return \App\Http\Resources\VenteGroupeeResource
     */
    public function show(VenteGroupee $venteGroupee)
    {
        Gate::authorize('view', $venteGroupee);

        $venteGroupee->load(['produit', 'acheteur']);

        return new VenteGroupeeResource($venteGroupee);
    }

    /**
     * Supprimer une vente groupée.
     *
     * Vérifie l'autorisation via Gate::authorize('delete', $venteGroupee).
     *
     * @param  \App\Models\VenteGroupee  $venteGroupee
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(VenteGroupee $venteGroupee)
    {
        Gate::authorize('delete', $venteGroupee);

        $venteGroupee->delete();

        return response()->json(['message' => 'Vente supprimée.']);
    }
}
