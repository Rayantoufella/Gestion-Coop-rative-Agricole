<?php

namespace Database\Factories;

use App\Models\DepotStock;
use App\Models\Membre;
use App\Models\Produit;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepotStockFactory extends Factory
{
    protected $model = DepotStock::class;

    // Données fictives pour un dépôt de stock.
    public function definition(): array
    {
        return [
            'membre_id' => Membre::factory(),
            'produit_id' => Produit::factory(),
            'date_depot' => fake()->date(),
            'quantite' => fake()->randomFloat(2, 1, 1000),
        ];
    }
}
