<?php

namespace Database\Seeders;

use App\Models\Membre;
use Illuminate\Database\Seeder;

class MembreSeeder extends Seeder
{
    public function run(): void
    {
        $membres = [
            ['code_membre' => 'GCA-2024-001', 'nom' => 'El Amrani', 'prenom' => 'Mohammed', 'telephone' => '0661234567', 'date_adhesion' => '2024-03-15', 'statut' => 'actif'],
            ['code_membre' => 'GCA-2024-002', 'nom' => 'Bennani', 'prenom' => 'Fatima Zahra', 'telephone' => '0662345678', 'date_adhesion' => '2024-04-02', 'statut' => 'actif'],
            ['code_membre' => 'GCA-2024-003', 'nom' => 'Ait Lahcen', 'prenom' => 'Youssef', 'telephone' => '0663456789', 'date_adhesion' => '2024-05-20', 'statut' => 'actif'],
            ['code_membre' => 'GCA-2024-004', 'nom' => 'El Idrissi', 'prenom' => 'Khadija', 'telephone' => '0664567890', 'date_adhesion' => '2024-06-11', 'statut' => 'actif'],
            ['code_membre' => 'GCA-2024-005', 'nom' => 'Ouazzani', 'prenom' => 'Rachid', 'telephone' => '0665678901', 'date_adhesion' => '2024-09-08', 'statut' => 'actif'],
            ['code_membre' => 'GCA-2024-006', 'nom' => 'Tazi', 'prenom' => 'Samira', 'telephone' => '0666789012', 'date_adhesion' => '2024-11-25', 'statut' => 'actif'],
            ['code_membre' => 'GCA-2025-007', 'nom' => 'Berrada', 'prenom' => 'Hassan', 'telephone' => '0667890123', 'date_adhesion' => '2025-01-14', 'statut' => 'actif'],
            ['code_membre' => 'GCA-2025-008', 'nom' => 'Lahlou', 'prenom' => 'Nadia', 'telephone' => '0668901234', 'date_adhesion' => '2025-02-19', 'statut' => 'actif'],
            ['code_membre' => 'GCA-2025-009', 'nom' => 'Chraibi', 'prenom' => 'Abdelhak', 'telephone' => '0669012345', 'date_adhesion' => '2025-03-27', 'statut' => 'actif'],
            ['code_membre' => 'GCA-2025-010', 'nom' => 'Naciri', 'prenom' => 'Amina', 'telephone' => '0670123456', 'date_adhesion' => '2025-04-30', 'statut' => 'actif'],
            ['code_membre' => 'GCA-2025-011', 'nom' => 'El Fassi', 'prenom' => 'Meryem', 'telephone' => '0671234567', 'date_adhesion' => '2025-06-05', 'statut' => 'inactif'],
            ['code_membre' => 'GCA-2025-012', 'nom' => 'Bouzid', 'prenom' => 'Omar', 'telephone' => '0672345678', 'date_adhesion' => '2025-07-21', 'statut' => 'inactif'],
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
