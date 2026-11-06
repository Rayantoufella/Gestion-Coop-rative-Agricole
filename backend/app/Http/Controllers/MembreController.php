<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMembreRequest;
use App\Http\Requests\UpdateMembreRequest;
use App\Http\Resources\MembreResource;
use App\Models\Membre;
use Illuminate\Support\Facades\Gate;

class MembreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        Gate::authorize('viewAny', Membre::class);

        return MembreResource::collection(Membre::orderBy('nom')->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMembreRequest $request)
    {
        //
        Gate::authorize('create', Membre::class);
        $membre = Membre::create($request->validated());

        return (new MembreResource($membre))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Membre $membre)
    {
        //
        Gate::authorize('view', $membre);

        return new MembreResource($membre);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMembreRequest $request, Membre $membre)
    {
        //
        Gate::authorize('update', $membre);
        $membre->update($request->validated());

        return new MembreResource($membre);

    }

    /**
     * Remove the specified resource from storage.
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
