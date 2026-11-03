<?php

namespace Database\Seeders;

use App\Models\Cotisation;
use App\Models\Membre;
use Illuminate\Database\Seeder;

class CotisationSeeder extends Seeder
{
    public function run(): void
    {
        // S'assurer qu'il existe des membres.
        if (Membre::count() === 0) {
            Membre::factory()->count(10)->create();
        }

        $membres = Membre::pluck('id');

        // Créer 20 cotisations.
        for ($i = 0; $i < 20; $i++) {
            Cotisation::factory()->create([
                'membre_id' => $membres->random(),
            ]);
        }
    }
}
