<?php

namespace Database\Factories;

use App\Models\Cotisation;
use App\Models\Membre;
use Illuminate\Database\Eloquent\Factories\Factory;

class CotisationFactory extends Factory
{
    protected $model = Cotisation::class;

    // Données fictives pour une cotisation.
    public function definition(): array
    {
        return [
            'membre_id' => Membre::factory(),
            'periode' => fake()->date('m/Y'),
            'montant' => fake()->randomFloat(2, 100, 5000),
            'date_paiement' => fake()->date(),
        ];
    }
}
