<?php

namespace Database\Factories;

use App\Models\Acheteur;
use Illuminate\Database\Eloquent\Factories\Factory;

class AcheteurFactory extends Factory
{
    protected $model = Acheteur::class;

    // Données fictives pour un acheteur.
    public function definition(): array
    {
        return [
            'nom' => fake()->name(),
            'telephone' => fake()->phoneNumber(),
        ];
    }
}
