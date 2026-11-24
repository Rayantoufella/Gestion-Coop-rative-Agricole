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
            ['nom' => 'Blé tendre', 'categorie' => 'Céréales', 'unite' => 'tonne'],
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
