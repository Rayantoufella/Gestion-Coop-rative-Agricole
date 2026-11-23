<?php

namespace Database\Seeders;

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
        // Données de démonstration fictives des 6 tables.
        $this->call([
            MembreSeeder::class,
            ProduitSeeder::class,
            AcheteurSeeder::class,
            CotisationSeeder::class,
            DepotStockSeeder::class,
            VenteGroupeeSeeder::class,
        ]);

        // Comptes de démonstration : un administrateur et un gestionnaire.
        $this->call([
            UserSeeder::class,
        ]);
    }
}
