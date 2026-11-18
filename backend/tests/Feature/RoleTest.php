<?php

namespace Tests\Feature;

use App\Models\Acheteur;
use App\Models\DepotStock;
use App\Models\Membre;
use App\Models\Produit;
use App\Models\User;
use App\Models\VenteGroupee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleTest extends TestCase
{
    // Vide la base de test avant chaque test
    use RefreshDatabase;

    public function test_utilisateur_non_connecte_ne_peut_pas_lister_les_membres(): void
    {
        // 1. Préparer : aucun utilisateur connecté

        // 2. Agir : appeler la liste des membres sans connexion
        $response = $this->getJson('/api/membres');

        // 3. Vérifier : l'API refuse l'accès
        $response->assertStatus(401);
    }

    public function test_gestionnaire_peut_lister_les_membres(): void
    {
        // 1. Préparer : un gestionnaire et un membre
        $user = User::factory()->create(['role' => 'gestionnaire']);
        Membre::factory()->create();

        // 2. Agir : demander la liste des membres
        $response = $this->actingAs($user)->getJson('/api/membres');

        // 3. Vérifier : la lecture est autorisée
        $response->assertStatus(200);
    }

    public function test_gestionnaire_ne_peut_pas_creer_un_membre(): void
    {
        // 1. Préparer : un utilisateur gestionnaire
        $user = User::factory()->create(['role' => 'gestionnaire']);

        // 2. Agir : envoyer des données valides pour créer un membre
        $response = $this->actingAs($user)->postJson('/api/membres', [
            'code_membre' => 'MEM-1001',
            'nom' => 'Diallo',
            'prenom' => 'Awa',
            'telephone' => '770000001',
            'date_adhesion' => '2026-01-15',
            'statut' => 'actif',
        ]);

        // 3. Vérifier : la création est interdite au gestionnaire
        $response->assertStatus(403);
    }

    public function test_gestionnaire_ne_peut_pas_supprimer_un_membre(): void
    {
        // 1. Préparer : un gestionnaire et un membre
        $user = User::factory()->create(['role' => 'gestionnaire']);
        $membre = Membre::factory()->create();

        // 2. Agir : demander la suppression du membre
        $response = $this->actingAs($user)
                         ->deleteJson("/api/membres/{$membre->id}");

        // 3. Vérifier : la suppression est interdite au gestionnaire
        $response->assertStatus(403);
    }

    public function test_gestionnaire_ne_peut_pas_lister_les_cotisations(): void
    {
        // 1. Préparer : un utilisateur gestionnaire
        $user = User::factory()->create(['role' => 'gestionnaire']);

        // 2. Agir : demander la liste des cotisations
        $response = $this->actingAs($user)->getJson('/api/cotisations');

        // 3. Vérifier : l'accès est interdit au gestionnaire
        $response->assertStatus(403);
    }

    public function test_gestionnaire_ne_peut_pas_creer_une_cotisation(): void
    {
        // 1. Préparer : un gestionnaire et un membre existant
        $user = User::factory()->create(['role' => 'gestionnaire']);
        $membre = Membre::factory()->create();

        // 2. Agir : envoyer des données valides pour créer une cotisation
        $response = $this->actingAs($user)->postJson('/api/cotisations', [
            'membre_id' => $membre->id,
            'periode' => '01/2026',
            'montant' => 1000,
            'date_paiement' => '2026-01-20',
        ]);

        // 3. Vérifier : la création est interdite au gestionnaire
        $response->assertStatus(403);
    }

    public function test_admin_peut_creer_une_vente_groupee(): void
    {
        // 1. Préparer : un admin, un produit avec un dépôt de 10 et un acheteur
        $user = User::factory()->create(['role' => 'admin']);
        $produit = Produit::factory()->create();
        DepotStock::factory()->create([
            'produit_id' => $produit->id,
            'quantite' => 10,
        ]);
        $acheteur = Acheteur::factory()->create();

        // 2. Agir : vendre 5 avec un stock suffisant
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

    public function test_admin_peut_supprimer_une_vente(): void
    {
        // 1. Préparer : un admin et une vente existante
        $user = User::factory()->create(['role' => 'admin']);
        $vente = VenteGroupee::factory()->create();

        // 2. Agir : demander la suppression de la vente
        $response = $this->actingAs($user)
                         ->deleteJson("/api/ventes-groupees/{$vente->id}");

        // 3. Vérifier : la vente est supprimée
        $response->assertStatus(200);
    }

    public function test_admin_peut_creer_un_acheteur(): void
    {
        // 1. Préparer : un utilisateur admin
        $user = User::factory()->create(['role' => 'admin']);

        // 2. Agir : envoyer des données valides pour créer un acheteur
        $response = $this->actingAs($user)->postJson('/api/acheteurs', [
            'nom' => 'Marché central',
            'telephone' => '770000003',
        ]);

        // 3. Vérifier : l'acheteur est créé
        $response->assertStatus(201);
    }

    public function test_admin_peut_supprimer_un_acheteur(): void
    {
        // 1. Préparer : un admin et un acheteur existant
        $user = User::factory()->create(['role' => 'admin']);
        $acheteur = Acheteur::factory()->create();

        // 2. Agir : demander la suppression de l'acheteur
        $response = $this->actingAs($user)
                         ->deleteJson("/api/acheteurs/{$acheteur->id}");

        // 3. Vérifier : l'acheteur est supprimé
        $response->assertStatus(200);
    }

    public function test_admin_peut_creer_un_membre(): void
    {
        // 1. Préparer : un utilisateur admin
        $user = User::factory()->create(['role' => 'admin']);

        // 2. Agir : envoyer des données valides pour créer un membre
        $response = $this->actingAs($user)->postJson('/api/membres', [
            'code_membre' => 'MEM-1002',
            'nom' => 'Traoré',
            'prenom' => 'Moussa',
            'telephone' => '770000002',
            'date_adhesion' => '2026-01-15',
            'statut' => 'actif',
        ]);

        // 3. Vérifier : le membre est créé
        $response->assertStatus(201);
    }
}
