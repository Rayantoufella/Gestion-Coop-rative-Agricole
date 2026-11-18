<?php

namespace Database\Seeders;

use App\Models\Produit;
use Illuminate\Database\Seeder;

class ProduitSeeder extends Seeder
{
    public function run(): void
    {
        $produits = [
            ['nom' => "Huile d'olive extra vierge", 'categorie' => 'Produits transformés', 'unite' => 'litre'],
            ['nom' => 'Dattes Majhoul', 'categorie' => 'Fruits', 'unite' => 'kg'],
            ['nom' => 'Oranges Maroc Late', 'categorie' => 'Fruits', 'unite' => 'tonne'],
            ['nom' => 'Tomates rondes', 'categorie' => 'Légumes', 'unite' => 'tonne'],
            ['nom' => 'Pommes de terre', 'categorie' => 'Légumes', 'unite' => 'tonne'],
            ['nom' => 'Blé tendre', 'categorie' => 'Céréales', 'unite' => 'tonne'],
            ['nom' => 'Maïs', 'categorie' => 'Céréales', 'unite' => 'tonne'],
            ['nom' => 'Orge', 'categorie' => 'Céréales', 'unite' => 'tonne'],
            ['nom' => 'Lentilles', 'categorie' => 'Légumineuses', 'unite' => 'sac'],
            ['nom' => 'Fèves', 'categorie' => 'Légumineuses', 'unite' => 'sac'],
            ['nom' => 'Miel de thym', 'categorie' => 'Produits transformés', 'unite' => 'kg'],
            ['nom' => 'Amandes', 'categorie' => 'Fruits', 'unite' => 'kg'],
        ];

        foreach ($produits as $produit) {
            Produit::updateOrCreate(
                ['nom' => $produit['nom']],
                [
                    'categorie' => $produit['categorie'],
                    'unite' => $produit['unite'],
                ]
            );
        }
    }
}
