<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAcheteurRequest;
use App\Http\Requests\UpdateAcheteurRequest;
use App\Http\Resources\AcheteurResource;
use App\Models\Acheteur;
use Illuminate\Support\Facades\Gate;

class AcheteurController extends Controller
{
    /**
     * Afficher la liste des acheteurs.
     */
    public function index()
    {
        Gate::authorize('viewAny', Acheteur::class);

        $acheteurs = Acheteur::orderBy('nom')->paginate(10);

        return AcheteurResource::collection($acheteurs);
    }

    /**
     * Créer un acheteur.
     */
    public function store(StoreAcheteurRequest $request)
    {
        Gate::authorize('create', Acheteur::class);

        $acheteur = Acheteur::create($request->validated());

        return (new AcheteurResource($acheteur))->response()->setStatusCode(201);
    }

    /**
     * Afficher un acheteur.
     */
    public function show(Acheteur $acheteur)
    {
        Gate::authorize('view', $acheteur);

        return new AcheteurResource($acheteur);
    }

    /**
     * Modifier un acheteur.
     */
    public function update(UpdateAcheteurRequest $request, Acheteur $acheteur)
    {
        Gate::authorize('update', $acheteur);

        $acheteur->update($request->validated());

        return new AcheteurResource($acheteur);
    }

    /**
     * Supprimer un acheteur.
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
