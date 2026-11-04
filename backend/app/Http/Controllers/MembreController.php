<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMembreRequest;
use App\Http\Requests\UpdateMembreRequest;
use App\Http\Resources\MembreResource;
use App\Models\Membre;

class MembreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return MembreResource::collection(Membre::orderBy('nom')->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMembreRequest $request)
    {
        //
        $membre = Membre::create($request->validated());

        return (new MembreResource($membre))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Membre $membre)
    {
        //
        return new MembreResource($membre);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMembreRequest $request, Membre $membre)
    {
        //
        $membre->update($request->validated());

        return new MembreResource($membre);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Membre $membre)
    {
        //
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
