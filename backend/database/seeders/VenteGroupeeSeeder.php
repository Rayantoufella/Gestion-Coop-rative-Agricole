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
        // S'assurer qu'il existe des produits et des acheteurs.
        if (Produit::count() === 0) {
            Produit::factory()->count(10)->create();
        }

        if (Acheteur::count() === 0) {
            Acheteur::factory()->count(10)->create();
        }

        $produits = Produit::pluck('id');
        $acheteurs = Acheteur::pluck('id');

        // Créer 20 ventes groupées.
        for ($i = 0; $i < 20; $i++) {
            VenteGroupee::factory()->create([
                'produit_id' => $produits->random(),
                'acheteur_id' => $acheteurs->random(),
            ]);
        }
    }
}
