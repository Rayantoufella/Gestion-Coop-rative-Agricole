<?php

namespace Database\Seeders;

use App\Models\Acheteur;
use Illuminate\Database\Seeder;

class AcheteurSeeder extends Seeder
{
    public function run(): void
    {
        // Créer 10 acheteurs de test.
        Acheteur::factory()->count(10)->create();
    }
}
