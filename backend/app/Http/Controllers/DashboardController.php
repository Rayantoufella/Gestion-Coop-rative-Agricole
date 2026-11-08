<?php

namespace App\Http\Controllers;

use App\Models\Cotisation;
use App\Models\Membre;
use App\Models\Produit;
use App\Models\VenteGroupee;

class DashboardController extends Controller
{
    // GET /api/dashboard
    public function index()
    {
        // Membres.
        $totalMembres = Membre::count();
        $membresActifs = Membre::where('statut', 'actif')->count();

        // Cotisations du mois.
        $cotisationsMois = Cotisation::whereMonth('date_paiement', now()->month)
            ->whereYear('date_paiement', now()->year)
            ->get();

        $nombreCotisations = $cotisationsMois->count();
        $montantCotisations = $cotisationsMois->sum('montant');

        // Ventes du mois.
        $ventesMois = VenteGroupee::whereMonth('date_vente', now()->month)
            ->whereYear('date_vente', now()->year)
            ->get();

        $chiffreAffaires = $ventesMois->sum(function ($vente) {
            return $vente->quantite * $vente->prix_unitaire;
        });

        // Stock de tous les produits.
        $produits = Produit::withSum('depotsStock as total_depots', 'quantite')
            ->withSum('ventesGroupees as total_ventes', 'quantite')
            ->orderBy('nom')
            ->get();

        $stock = $produits->map(function ($produit) {
            $totalDepots = $produit->total_depots ?? 0;
            $totalVentes = $produit->total_ventes ?? 0;

            return [
                'id' => $produit->id,
                'nom' => $produit->nom,
                'unite' => $produit->unite,
                'total_depots' => $totalDepots,
                'total_ventes' => $totalVentes,
                'stock_disponible' => $totalDepots - $totalVentes,
            ];
        })->values();

        // Cinq dernières ventes.
        $dernieresVentes = VenteGroupee::with(['produit', 'acheteur'])
            ->orderBy('date_vente', 'desc')
            ->limit(5)
            ->get();

        $ventesRecentes = $dernieresVentes->map(function ($vente) {
            return [
                'id' => $vente->id,
                'date_vente' => $vente->date_vente?->format('Y-m-d'),
                'produit' => $vente->produit?->nom,
                'acheteur' => $vente->acheteur?->nom,
                'quantite' => $vente->quantite,
                'prix_unitaire' => $vente->prix_unitaire,
            ];
        })->values();

        return response()->json([
            'membres' => [
                'total' => $totalMembres,
                'actifs' => $membresActifs,
            ],
            'cotisations_mois' => [
                'nombre' => $nombreCotisations,
                'montant' => $montantCotisations,
            ],
            'ventes_mois' => [
                'nombre' => $ventesMois->count(),
                'chiffre_affaires' => $chiffreAffaires,
            ],
            'stock' => $stock,
            'ventes_recentes' => $ventesRecentes,
        ]);
    }
}
