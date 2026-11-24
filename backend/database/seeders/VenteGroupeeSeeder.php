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

        // Les quantités vendues restent inférieures aux dépôts (stock jamais négatif).
        $ventes = [
            ['produit' => "Huile d'olive extra vierge", 'acheteur' => 'Épicerie Al Amal', 'date_vente' => $date(2), 'quantite' => 200, 'prix_unitaire' => 95],
            ['produit' => "Huile d'olive extra vierge", 'acheteur' => "Restaurant Le Jardin d'Argan", 'date_vente' => $date(0, 5), 'quantite' => 150, 'prix_unitaire' => 95],
            ['produit' => 'Dattes Majhoul', 'acheteur' => 'Grossiste Sud Primeurs', 'date_vente' => $date(1), 'quantite' => 500, 'prix_unitaire' => 35],
            ['produit' => 'Blé tendre', 'acheteur' => 'Grossiste Sud Primeurs', 'date_vente' => $date(1, 10), 'quantite' => 3, 'prix_unitaire' => 3200],
            ['produit' => 'Blé tendre', 'acheteur' => 'Épicerie Al Amal', 'date_vente' => $date(0, 12), 'quantite' => 2, 'prix_unitaire' => 3200],
            ['produit' => 'Miel de thym', 'acheteur' => "Restaurant Le Jardin d'Argan", 'date_vente' => $date(0, 8), 'quantite' => 100, 'prix_unitaire' => 220],
            ['produit' => 'Amandes', 'acheteur' => 'Épicerie Al Amal', 'date_vente' => $date(1), 'quantite' => 120, 'prix_unitaire' => 90],
            ['produit' => 'Amandes', 'acheteur' => 'Grossiste Sud Primeurs', 'date_vente' => $date(0, 3), 'quantite' => 60, 'prix_unitaire' => 90],
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
