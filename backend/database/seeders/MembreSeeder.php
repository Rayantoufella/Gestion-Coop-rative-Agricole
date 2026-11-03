<?php

namespace Database\Seeders;

use App\Models\Membre;
use Illuminate\Database\Seeder;

class MembreSeeder extends Seeder
{
    public function run(): void
    {
        // Créer 10 membres de test.
        Membre::factory()->count(10)->create();
    }
}
