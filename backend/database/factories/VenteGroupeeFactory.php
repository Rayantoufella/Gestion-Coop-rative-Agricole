<?php

namespace Database\Factories;

use App\Models\Acheteur;
use App\Models\Produit;
use App\Models\VenteGroupee;
use Illuminate\Database\Eloquent\Factories\Factory;

class VenteGroupeeFactory extends Factory
{
    protected $model = VenteGroupee::class;

    // Données fictives pour une vente groupée.
    public function definition(): array
    {
        return [
            'produit_id' => Produit::factory(),
            'acheteur_id' => Acheteur::factory(),
            'date_vente' => fake()->date(),
            'quantite' => fake()->randomFloat(2, 1, 1000),
            'prix_unitaire' => fake()->randomFloat(2, 100, 10000),
        ];
    }
}
