<?php

namespace Database\Seeders;

use App\Models\Acheteur;
use Illuminate\Database\Seeder;

class AcheteurSeeder extends Seeder
{
    public function run(): void
    {
        // Acheteurs fictifs : noms inventés et numéros factices (05 00 00 00 xx).
        $acheteurs = [
            ['nom' => 'Épicerie Al Amal', 'telephone' => '0500000001'],
            ['nom' => "Restaurant Le Jardin d'Argan", 'telephone' => '0500000002'],
            ['nom' => 'Grossiste Sud Primeurs', 'telephone' => '0500000003'],
        ];

        foreach ($acheteurs as $acheteur) {
            Acheteur::updateOrCreate(
                ['nom' => $acheteur['nom']],
                ['telephone' => $acheteur['telephone']]
            );
        }
    }
}
