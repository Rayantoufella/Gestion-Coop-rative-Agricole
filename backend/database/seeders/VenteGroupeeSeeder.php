<?php

namespace Database\Seeders;

use App\Models\Acheteur;
use App\Models\Produit;
use App\Models\VenteGroupee;
use Illuminate\Database\Seeder;

class VenteGroupeeSeeder extends Seeder
{
    public function run(): void
    {
        $produits = Produit::pluck('id', 'nom');
        $acheteurs = Acheteur::pluck('id', 'nom');

        $date = static fn (int $mois, int $jours = 0): string => now()->subMonths($mois)->subDays($jours)->toDateString();

        $ventes = [
            ['produit' => "Huile d'olive extra vierge", 'acheteur' => 'Marjane Holding (Casablanca)', 'date_vente' => $date(3), 'quantite' => 200, 'prix_unitaire' => 95],
            ['produit' => "Huile d'olive extra vierge", 'acheteur' => 'Hôtel Atlas (Marrakech)', 'date_vente' => $date(0, 5), 'quantite' => 150, 'prix_unitaire' => 95],
            ['produit' => 'Dattes Majhoul', 'acheteur' => "Label'Vie (Rabat)", 'date_vente' => $date(2), 'quantite' => 500, 'prix_unitaire' => 35],
            ['produit' => 'Dattes Majhoul', 'acheteur' => 'Souk El Had (Agadir)', 'date_vente' => $date(0, 10), 'quantite' => 300, 'prix_unitaire' => 35],
            ['produit' => 'Oranges Maroc Late', 'acheteur' => 'Marché de gros de Casablanca', 'date_vente' => $date(1), 'quantite' => 2, 'prix_unitaire' => 6000],
            ['produit' => 'Oranges Maroc Late', 'acheteur' => 'Grossiste Derb Omar (Casablanca)', 'date_vente' => $date(0, 7), 'quantite' => 1, 'prix_unitaire' => 6000],
            ['produit' => 'Tomates rondes', 'acheteur' => 'Marché de gros de Casablanca', 'date_vente' => $date(0, 20), 'quantite' => 1.5, 'prix_unitaire' => 5000],
            ['produit' => 'Tomates rondes', 'acheteur' => 'Restaurant Dar Tajine (Marrakech)', 'date_vente' => $date(0, 4), 'quantite' => 1, 'prix_unitaire' => 5000],
            ['produit' => 'Pommes de terre', 'acheteur' => 'Marjane Holding (Casablanca)', 'date_vente' => $date(2), 'quantite' => 2.5, 'prix_unitaire' => 3500],
            ['produit' => 'Pommes de terre', 'acheteur' => 'Souk El Had (Agadir)', 'date_vente' => $date(0, 12), 'quantite' => 1.5, 'prix_unitaire' => 3500],
            ['produit' => 'Blé tendre', 'acheteur' => 'Coopérative Al Baraka (Agadir)', 'date_vente' => $date(3), 'quantite' => 3, 'prix_unitaire' => 3200],
            ['produit' => 'Blé tendre', 'acheteur' => 'Marché de gros de Casablanca', 'date_vente' => $date(0, 15), 'quantite' => 2, 'prix_unitaire' => 3200],
            ['produit' => 'Maïs', 'acheteur' => 'Coopérative Al Baraka (Agadir)', 'date_vente' => $date(2), 'quantite' => 1.2, 'prix_unitaire' => 2800],
            ['produit' => 'Maïs', 'acheteur' => 'Grossiste Derb Omar (Casablanca)', 'date_vente' => $date(0, 6), 'quantite' => 0.8, 'prix_unitaire' => 2800],
            ['produit' => 'Orge', 'acheteur' => 'Hôtel Atlas (Marrakech)', 'date_vente' => $date(1), 'quantite' => 1, 'prix_unitaire' => 2500],
            ['produit' => 'Orge', 'acheteur' => 'Restaurant Dar Tajine (Marrakech)', 'date_vente' => $date(0, 9), 'quantite' => 0.5, 'prix_unitaire' => 2500],
            ['produit' => 'Lentilles', 'acheteur' => "Label'Vie (Rabat)", 'date_vente' => $date(0, 20), 'quantite' => 10, 'prix_unitaire' => 700],
            ['produit' => 'Lentilles', 'acheteur' => 'Souk El Had (Agadir)', 'date_vente' => $date(0, 3), 'quantite' => 5, 'prix_unitaire' => 700],
            ['produit' => 'Fèves', 'acheteur' => 'Marché de gros de Casablanca', 'date_vente' => $date(1), 'quantite' => 12, 'prix_unitaire' => 450],
            ['produit' => 'Fèves', 'acheteur' => 'Coopérative Al Baraka (Agadir)', 'date_vente' => $date(0, 5), 'quantite' => 8, 'prix_unitaire' => 450],
            ['produit' => 'Miel de thym', 'acheteur' => 'Marjane Holding (Casablanca)', 'date_vente' => $date(2), 'quantite' => 100, 'prix_unitaire' => 220],
            ['produit' => 'Miel de thym', 'acheteur' => 'Hôtel Atlas (Marrakech)', 'date_vente' => $date(0), 'quantite' => 50, 'prix_unitaire' => 220],
            ['produit' => 'Amandes', 'acheteur' => "Label'Vie (Rabat)", 'date_vente' => $date(1), 'quantite' => 120, 'prix_unitaire' => 90],
            ['produit' => 'Amandes', 'acheteur' => 'Souk El Had (Agadir)', 'date_vente' => $date(0, 8), 'quantite' => 80, 'prix_unitaire' => 90],
        ];

        foreach ($ventes as $vente) {
            VenteGroupee::updateOrCreate(
                [
                    'produit_id' => $produits[$vente['produit']],
                    'acheteur_id' => $acheteurs[$vente['acheteur']],
                    'date_vente' => $vente['date_vente'],
                ],
                [
                    'quantite' => $vente['quantite'],
                    'prix_unitaire' => $vente['prix_unitaire'],
                ]
            );
        }
    }
}
