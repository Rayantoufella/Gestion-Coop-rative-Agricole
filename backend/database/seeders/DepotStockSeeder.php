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
            ['produit' => "Huile d'olive extra vierge", 'membre' => 'GCA-2024-001', 'date_depot' => $date(8), 'quantite' => 600],
            ['produit' => "Huile d'olive extra vierge", 'membre' => 'GCA-2024-003', 'date_depot' => $date(4), 'quantite' => 600],
            ['produit' => 'Dattes Majhoul', 'membre' => 'GCA-2024-002', 'date_depot' => $date(7), 'quantite' => 1300],
            ['produit' => 'Dattes Majhoul', 'membre' => 'GCA-2024-006', 'date_depot' => $date(4), 'quantite' => 1200],
            ['produit' => 'Oranges Maroc Late', 'membre' => 'GCA-2024-003', 'date_depot' => $date(9), 'quantite' => 4],
            ['produit' => 'Oranges Maroc Late', 'membre' => 'GCA-2025-007', 'date_depot' => $date(5), 'quantite' => 4],
            ['produit' => 'Tomates rondes', 'membre' => 'GCA-2024-004', 'date_depot' => $date(8), 'quantite' => 3],
            ['produit' => 'Tomates rondes', 'membre' => 'GCA-2025-010', 'date_depot' => $date(4), 'quantite' => 3],
            ['produit' => 'Pommes de terre', 'membre' => 'GCA-2024-005', 'date_depot' => $date(7), 'quantite' => 5],
            ['produit' => 'Pommes de terre', 'membre' => 'GCA-2024-001', 'date_depot' => $date(4), 'quantite' => 5],
            ['produit' => 'Blé tendre', 'membre' => 'GCA-2025-007', 'date_depot' => $date(9), 'quantite' => 8],
            ['produit' => 'Blé tendre', 'membre' => 'GCA-2025-009', 'date_depot' => $date(5), 'quantite' => 7],
            ['produit' => 'Maïs', 'membre' => 'GCA-2025-008', 'date_depot' => $date(7), 'quantite' => 4],
            ['produit' => 'Maïs', 'membre' => 'GCA-2024-004', 'date_depot' => $date(4), 'quantite' => 3],
            ['produit' => 'Orge', 'membre' => 'GCA-2024-001', 'date_depot' => $date(8), 'quantite' => 2.5],
            ['produit' => 'Orge', 'membre' => 'GCA-2024-005', 'date_depot' => $date(4), 'quantite' => 2.5],
            ['produit' => 'Lentilles', 'membre' => 'GCA-2024-002', 'date_depot' => $date(7), 'quantite' => 20],
            ['produit' => 'Lentilles', 'membre' => 'GCA-2024-006', 'date_depot' => $date(4), 'quantite' => 20],
            ['produit' => 'Fèves', 'membre' => 'GCA-2024-004', 'date_depot' => $date(9), 'quantite' => 30],
            ['produit' => 'Fèves', 'membre' => 'GCA-2025-007', 'date_depot' => $date(5), 'quantite' => 30],
            ['produit' => 'Miel de thym', 'membre' => 'GCA-2024-003', 'date_depot' => $date(8), 'quantite' => 300],
            ['produit' => 'Miel de thym', 'membre' => 'GCA-2025-008', 'date_depot' => $date(4), 'quantite' => 300],
            ['produit' => 'Amandes', 'membre' => 'GCA-2024-005', 'date_depot' => $date(7), 'quantite' => 400],
            ['produit' => 'Amandes', 'membre' => 'GCA-2025-009', 'date_depot' => $date(4), 'quantite' => 400],
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
