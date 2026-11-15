<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

// Un test Unit vérifie une logique pure, sans Laravel, sans route et sans base de données.
// Un test Feature vérifie une fonctionnalité complète de l'API (route + contrôleur + base de données).
class MontantTest extends TestCase
{
    public function test_montant_total_egal_quantite_fois_prix_unitaire(): void
    {
        // 1. Préparer : une quantité et un prix unitaire
        $quantite = 10;
        $prixUnitaire = 2.5;

        // 2. Agir : calculer le montant total
        $montantTotal = $quantite * $prixUnitaire;

        // 3. Vérifier : 10 × 2,5 = 25
        $this->assertEquals(25, $montantTotal);
    }
}
