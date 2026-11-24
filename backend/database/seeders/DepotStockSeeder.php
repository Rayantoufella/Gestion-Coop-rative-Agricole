<?php

namespace Database\Seeders;

use App\Models\DepotStock;
use App\Models\Membre;
use App\Models\Produit;
use Illuminate\Database\Seeder;

class DepotStockSeeder extends Seeder
{
    public function run(): void
    {
        $produits = Produit::pluck('id', 'nom');
        $membres = Membre::pluck('id', 'code_membre');

        $date = static fn (int $mois): string => now()->subMonths($mois)->toDateString();

        $depots = [
            ['produit' => "Huile d'olive extra vierge", 'membre' => 'GCA-2025-001', 'date_depot' => $date(6), 'quantite' => 600],
            ['produit' => "Huile d'olive extra vierge", 'membre' => 'GCA-2025-003', 'date_depot' => $date(3), 'quantite' => 400],
            ['produit' => 'Dattes Majhoul', 'membre' => 'GCA-2025-002', 'date_depot' => $date(5), 'quantite' => 800],
            ['produit' => 'Dattes Majhoul', 'membre' => 'GCA-2025-006', 'date_depot' => $date(2), 'quantite' => 500],
            ['produit' => 'Blé tendre', 'membre' => 'GCA-2025-004', 'date_depot' => $date(5), 'quantite' => 8],
            ['produit' => 'Blé tendre', 'membre' => 'GCA-2025-007', 'date_depot' => $date(2), 'quantite' => 6],
            ['produit' => 'Miel de thym', 'membre' => 'GCA-2025-005', 'date_depot' => $date(4), 'quantite' => 150],
            ['produit' => 'Amandes', 'membre' => 'GCA-2025-008', 'date_depot' => $date(3), 'quantite' => 300],
        ];

        foreach ($depots as $depot) {
            DepotStock::updateOrCreate(
                [
                    'membre_id' => $membres[$depot['membre']],
                    'produit_id' => $produits[$depot['produit']],
                    'date_depot' => $depot['date_depot'],
                ],
                ['quantite' => $depot['quantite']]
            );
        }
    }
}
