<?php

namespace Database\Seeders;

use App\Models\Membre;
use Illuminate\Database\Seeder;

class MembreSeeder extends Seeder
{
    public function run(): void
    {
        // Données fictives : noms inventés et numéros factices (06 00 00 00 xx).
        $membres = [
            ['code_membre' => 'GCA-2025-001', 'nom' => 'Amrani', 'prenom' => 'Salma', 'telephone' => '0600000001', 'date_adhesion' => '2025-01-10', 'statut' => 'actif'],
            ['code_membre' => 'GCA-2025-002', 'nom' => 'Benali', 'prenom' => 'Karim', 'telephone' => '0600000002', 'date_adhesion' => '2025-01-24', 'statut' => 'actif'],
            ['code_membre' => 'GCA-2025-003', 'nom' => 'Chafik', 'prenom' => 'Hiba', 'telephone' => '0600000003', 'date_adhesion' => '2025-02-15', 'statut' => 'actif'],
            ['code_membre' => 'GCA-2025-004', 'nom' => 'Daoudi', 'prenom' => 'Anas', 'telephone' => '0600000004', 'date_adhesion' => '2025-03-03', 'statut' => 'actif'],
            ['code_membre' => 'GCA-2025-005', 'nom' => 'El Kadiri', 'prenom' => 'Leïla', 'telephone' => '0600000005', 'date_adhesion' => '2025-03-21', 'statut' => 'actif'],
            ['code_membre' => 'GCA-2025-006', 'nom' => 'Fadili', 'prenom' => 'Mehdi', 'telephone' => '0600000006', 'date_adhesion' => '2025-04-08', 'statut' => 'actif'],
            ['code_membre' => 'GCA-2025-007', 'nom' => 'Ghazi', 'prenom' => 'Sara', 'telephone' => '0600000007', 'date_adhesion' => '2025-05-12', 'statut' => 'actif'],
            ['code_membre' => 'GCA-2025-008', 'nom' => 'Hamdani', 'prenom' => 'Ilyas', 'telephone' => '0600000008', 'date_adhesion' => '2025-06-02', 'statut' => 'actif'],
            ['code_membre' => 'GCA-2025-009', 'nom' => 'Idrissi', 'prenom' => 'Rania', 'telephone' => '0600000009', 'date_adhesion' => '2025-06-19', 'statut' => 'inactif'],
            ['code_membre' => 'GCA-2025-010', 'nom' => 'Jabri', 'prenom' => 'Yassine', 'telephone' => '0600000010', 'date_adhesion' => '2025-07-07', 'statut' => 'inactif'],
        ];

        foreach ($membres as $membre) {
            Membre::updateOrCreate(
                ['code_membre' => $membre['code_membre']],
                [
                    'nom' => $membre['nom'],
                    'prenom' => $membre['prenom'],
                    'telephone' => $membre['telephone'],
                    'date_adhesion' => $membre['date_adhesion'],
                    'statut' => $membre['statut'],
                ]
            );
        }
    }
}
