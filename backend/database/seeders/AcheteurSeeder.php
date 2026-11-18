<?php

namespace Database\Seeders;

use App\Models\Acheteur;
use Illuminate\Database\Seeder;

class AcheteurSeeder extends Seeder
{
    public function run(): void
    {
        $acheteurs = [
            ['nom' => 'Marjane Holding (Casablanca)', 'telephone' => '0522234567'],
            ['nom' => "Label'Vie (Rabat)", 'telephone' => '0537345678'],
            ['nom' => 'Marché de gros de Casablanca', 'telephone' => '0522456789'],
            ['nom' => 'Hôtel Atlas (Marrakech)', 'telephone' => '0524567890'],
            ['nom' => 'Coopérative Al Baraka (Agadir)', 'telephone' => '0528678901'],
            ['nom' => 'Grossiste Derb Omar (Casablanca)', 'telephone' => '0522789012'],
            ['nom' => 'Restaurant Dar Tajine (Marrakech)', 'telephone' => '0524890123'],
            ['nom' => 'Souk El Had (Agadir)', 'telephone' => '0528901234'],
        ];

        foreach ($acheteurs as $acheteur) {
            Acheteur::updateOrCreate(
                ['nom' => $acheteur['nom']],
                ['telephone' => $acheteur['telephone']]
            );
        }
    }
}
