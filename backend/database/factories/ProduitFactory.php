<?php

namespace Database\Factories;

use App\Models\Produit;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProduitFactory extends Factory
{
    protected $model = Produit::class;

    // Données fictives pour un produit.
    public function definition(): array
    {
        return [
            'nom' => fake()->word(),
            'categorie' => fake()->randomElement(['Céréales', 'Légumes', 'Fruits', 'Légumineuses']),
            'unite' => fake()->randomElement(['kg', 'sac', 'tonne', 'litre']),
        ];
    }
}
