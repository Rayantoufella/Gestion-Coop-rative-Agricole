<?php

use App\Http\Controllers\AcheteurController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CotisationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepotStockController;
use App\Http\Controllers\MembreController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\VenteGroupeeController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::apiResource('membres', MembreController::class);
    Route::apiResource('cotisations', CotisationController::class);
    Route::apiResource('produits', ProduitController::class);
    Route::apiResource('depots-stock', DepotStockController::class);
    Route::apiResource('acheteurs', AcheteurController::class);
    Route::apiResource('ventes-groupees', VenteGroupeeController::class)
        ->only(['index', 'store', 'show', 'destroy'])
        ->parameters(['ventes-groupees' => 'venteGroupee']);

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:admin,gestionnaire');
});
