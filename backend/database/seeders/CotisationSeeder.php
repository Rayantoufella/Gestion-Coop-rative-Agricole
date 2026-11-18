<?php

namespace Database\Seeders;

use App\Models\Cotisation;
use App\Models\Membre;
use Illuminate\Database\Seeder;

class CotisationSeeder extends Seeder
{
    public function run(): void
    {
        $membres = Membre::where('statut', 'actif')->orderBy('code_membre')->get();

        $periodes = [
            ['periode' => now()->format('m/Y'), 'date_paiement' => now()->toDateString()],
            ['periode' => now()->subMonth()->format('m/Y'), 'date_paiement' => now()->subMonth()->toDateString()],
        ];

        $montants = [200, 300, 500];

        foreach ($membres as $index => $membre) {
            foreach ($periodes as $periodIndex => $periode) {
                Cotisation::updateOrCreate(
                    [
                        'membre_id' => $membre->id,
                        'periode' => $periode['periode'],
                    ],
                    [
                        'montant' => $montants[($index + $periodIndex) % count($montants)],
                        'date_paiement' => $periode['date_paiement'],
                    ]
                );
            }
        }
    }
}
