<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProduitRequest;
use App\Http\Requests\UpdateProduitRequest;
use App\Http\Resources\ProduitResource;
use App\Models\Produit;
use Illuminate\Support\Facades\Gate;

/**
 * Contrôleur de gestion des produits.
 *
 * Gère les opérations CRUD sur les produits stockés et vendus.
 */
class ProduitController extends Controller
{
    /**
     * Afficher la liste paginée des produits.
     *
     * Vérifie l'autorisation via Gate::authorize('viewAny', Produit::class).
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        Gate::authorize('viewAny', Produit::class);

        return ProduitResource::collection(Produit::orderBy('nom')->paginate(10));
    }

    /**
     * Créer un produit.
     *
     * Vérifie l'autorisation via Gate::authorize('create', Produit::class).
     *
     * @param  \App\Http\Requests\StoreProduitRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreProduitRequest $request)
    {
        Gate::authorize('create', Produit::class);

        $produit = Produit::create($request->validated());

        return (new ProduitResource($produit))->response()->setStatusCode(201);
    }

    /**
     * Afficher un produit.
     *
     * Vérifie l'autorisation via Gate::authorize('view', $produit).
     *
     * @param  \App\Models\Produit  $produit
     * @return \App\Http\Resources\ProduitResource
     */
    public function show(Produit $produit)
    {
        Gate::authorize('view', $produit);

        return new ProduitResource($produit);
    }

    /**
     * Modifier un produit.
     *
     * Vérifie l'autorisation via Gate::authorize('update', $produit).
     *
     * @param  \App\Http\Requests\UpdateProduitRequest  $request
     * @param  \App\Models\Produit  $produit
     * @return \App\Http\Resources\ProduitResource
     */
    public function update(UpdateProduitRequest $request, Produit $produit)
    {
        Gate::authorize('update', $produit);

        $produit->update($request->validated());

        return new ProduitResource($produit);
    }

    /**
     * Supprimer un produit.
     *
     * Vérifie l'autorisation via Gate::authorize('delete', $produit).
     * Retourne une réponse 409 si le produit est utilisé par des dépôts ou des ventes.
     *
     * @param  \App\Models\Produit  $produit
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Produit $produit)
    {
        Gate::authorize('delete', $produit);

        // Refuser si le produit est déjà utilisé.
        if ($produit->depotsStock()->exists() || $produit->ventesGroupees()->exists()) {
            return response()->json([
                'message' => 'Suppression impossible : ce produit est utilisé par des dépôts ou des ventes.',
            ], 409);
        }

        $produit->delete();

        return response()->json([
            'message' => 'Produit supprimé.',
        ], 200);
    }
}
