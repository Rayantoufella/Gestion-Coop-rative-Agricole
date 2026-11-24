<?php

namespace Tests\Feature;

use App\Models\Acheteur;
use App\Models\DepotStock;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VenteGroupeeTest extends TestCase
{
    // Vide la base de test avant chaque test
    use RefreshDatabase;

    public function test_vente_superieure_au_stock_renvoie_422(): void
    {
        // 1. Préparer : un gestionnaire, un produit avec un dépôt de 10 et un acheteur
        $user = User::factory()->create(['role' => 'gestionnaire']);
        $produit = Produit::factory()->create();
        DepotStock::factory()->create([
            'produit_id' => $produit->id,
            'quantite' => 10,
        ]);
        $acheteur = Acheteur::factory()->create();

        // 2. Agir : vendre 20 alors que le stock disponible est de 10
        $response = $this->actingAs($user)->postJson('/api/ventes-groupees', [
            'produit_id' => $produit->id,
            'acheteur_id' => $acheteur->id,
            'date_vente' => '2026-01-20',
            'quantite' => 20,
            'prix_unitaire' => 2.5,
        ]);

        // 3. Vérifier : la vente est refusée avec le message de stock insuffisant
        $response->assertStatus(422)
                 ->assertJson(['message' => 'Stock insuffisant.']);
    }

    public function test_vente_dans_la_limite_du_stock_renvoie_201(): void
    {
        // 1. Préparer : un gestionnaire, un produit avec un dépôt de 10 et un acheteur
        $user = User::factory()->create(['role' => 'gestionnaire']);
        $produit = Produit::factory()->create();
        DepotStock::factory()->create([
            'produit_id' => $produit->id,
            'quantite' => 10,
        ]);
        $acheteur = Acheteur::factory()->create();

        // 2. Agir : vendre 5 alors que le stock disponible est de 10
        $response = $this->actingAs($user)->postJson('/api/ventes-groupees', [
            'produit_id' => $produit->id,
            'acheteur_id' => $acheteur->id,
            'date_vente' => '2026-01-20',
            'quantite' => 5,
            'prix_unitaire' => 2.5,
        ]);

        // 3. Vérifier : la vente est créée
        $response->assertStatus(201);
    }
}
