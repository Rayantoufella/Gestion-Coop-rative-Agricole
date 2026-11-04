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
        // S'assurer qu'il existe des membres et des produits.
        if (Membre::count() === 0) {
            Membre::factory()->count(10)->create();
        }

        if (Produit::count() === 0) {
            Produit::factory()->count(10)->create();
        }

        $membres = Membre::pluck('id');
        $produits = Produit::pluck('id');

        // Créer 20 dépôts de stock.
        for ($i = 0; $i < 20; $i++) {
            DepotStock::factory()->create([
                'membre_id' => $membres->random(),
                'produit_id' => $produits->random(),
            ]);
        }
    }
}
