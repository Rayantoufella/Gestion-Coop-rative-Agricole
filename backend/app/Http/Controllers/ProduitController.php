<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProduitRequest;
use App\Http\Requests\UpdateProduitRequest;
use App\Http\Resources\ProduitResource;
use App\Models\Produit;
use Illuminate\Support\Facades\Gate;

class ProduitController extends Controller
{
    /**
     * Afficher la liste des produits.
     */
    public function index()
    {
        Gate::authorize('viewAny', Produit::class);

        return ProduitResource::collection(Produit::orderBy('nom')->paginate(10));
    }

    /**
     * Créer un produit.
     */
    public function store(StoreProduitRequest $request)
    {
        Gate::authorize('create', Produit::class);

        $produit = Produit::create($request->validated());

        return (new ProduitResource($produit))->response()->setStatusCode(201);
    }

    /**
     * Afficher un produit.
     */
    public function show(Produit $produit)
    {
        Gate::authorize('view', $produit);

        return new ProduitResource($produit);
    }

    /**
     * Modifier un produit.
     */
    public function update(UpdateProduitRequest $request, Produit $produit)
    {
        Gate::authorize('update', $produit);

        $produit->update($request->validated());

        return new ProduitResource($produit);
    }

    /**
     * Supprimer un produit.
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
