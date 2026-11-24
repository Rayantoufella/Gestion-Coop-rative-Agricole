<?php

namespace Tests\Feature;

use App\Models\Cotisation;
use App\Models\DepotStock;
use App\Models\Membre;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuppressionTest extends TestCase
{
    // Vide la base de test avant chaque test
    use RefreshDatabase;

    public function test_gestionnaire_ne_peut_pas_supprimer_un_produit_avec_depot(): void
    {
        // 1. Préparer : un gestionnaire et un produit qui a un dépôt
        $user = User::factory()->create(['role' => 'gestionnaire']);
        $produit = Produit::factory()->create();
        DepotStock::factory()->create([
            'produit_id' => $produit->id,
        ]);

        // 2. Agir : demander la suppression du produit
        $response = $this->actingAs($user)
                         ->deleteJson("/api/produits/{$produit->id}");

        // 3. Vérifier : la suppression est refusée
        $response->assertStatus(409);
    }

    public function test_gestionnaire_peut_supprimer_un_produit_sans_dependance(): void
    {
        // 1. Préparer : un gestionnaire et un produit sans dépôt ni vente
        $user = User::factory()->create(['role' => 'gestionnaire']);
        $produit = Produit::factory()->create();

        // 2. Agir : demander la suppression du produit
        $response = $this->actingAs($user)
                         ->deleteJson("/api/produits/{$produit->id}");

        // 3. Vérifier : le produit est supprimé
        $response->assertStatus(200);
    }

    public function test_admin_ne_peut_pas_supprimer_un_membre_avec_cotisation(): void
    {
        // 1. Préparer : un admin et un membre qui a une cotisation
        $user = User::factory()->create(['role' => 'admin']);
        $membre = Membre::factory()->create();
        Cotisation::factory()->create([
            'membre_id' => $membre->id,
        ]);

        // 2. Agir : demander la suppression du membre
        $response = $this->actingAs($user)
                         ->deleteJson("/api/membres/{$membre->id}");

        // 3. Vérifier : la suppression est refusée (conflit) et le membre existe toujours
        $response->assertStatus(409);
        $this->assertDatabaseHas('membres', ['id' => $membre->id]);
    }
}
