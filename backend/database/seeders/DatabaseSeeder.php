<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Créer les données de test des 6 tables.
        $this->call([
            MembreSeeder::class,
            ProduitSeeder::class,
            AcheteurSeeder::class,
            CotisationSeeder::class,
            DepotStockSeeder::class,
            VenteGroupeeSeeder::class,
        ]);

        $this->call([
            UserSeeder::class,
        ]);
    }
}
