<?php

namespace Database\Seeders;

use App\Models\Produit;
use Illuminate\Database\Seeder;

class ProduitSeeder extends Seeder
{
    public function run(): void
    {
        // Créer 10 produits de test.
        Produit::factory()->count(10)->create();
    }
}
