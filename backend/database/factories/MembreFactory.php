<?php

namespace Database\Factories;

use App\Models\Membre;
use Illuminate\Database\Eloquent\Factories\Factory;

class MembreFactory extends Factory
{
    protected $model = Membre::class;

    // Données fictives pour un membre.
    public function definition(): array
    {
        return [
            'code_membre' => fake()->unique()->bothify('MEM-####'),
            'nom' => fake()->lastName(),
            'prenom' => fake()->firstName(),
            'telephone' => fake()->phoneNumber(),
            'date_adhesion' => fake()->date(),
            'statut' => fake()->randomElement(['actif', 'inactif']),
        ];
    }
}
