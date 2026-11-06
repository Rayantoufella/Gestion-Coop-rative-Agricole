<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCotisationRequest;
use App\Http\Requests\UpdateCotisationRequest;
use App\Http\Resources\CotisationResource;
use App\Models\Cotisation;
use Illuminate\Support\Facades\Gate;

class CotisationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        Gate::authorize('viewAny', Cotisation::class);

        $cotisations = Cotisation::with('membre')->orderBy('date_paiement', 'desc')->paginate(10);

        return CotisationResource::collection($cotisations);
    }

    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
     */
    public function show(Cotisation $cotisation)
    {
        //
        Gate::authorize('view', $cotisation);

        $cotisation->load('membre');

        return new CotisationResource($cotisation);
    }

    /**
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
     */
    public function destroy(Cotisation $cotisation)
    {
        //
        Gate::authorize('delete', $cotisation);

        $cotisation->delete();

        return response()->json(['message' => 'Cotisation supprimée avec succès.'], 200);
    }
}
