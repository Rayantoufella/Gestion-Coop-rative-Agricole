<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UtilisateurTest extends TestCase
{
    // Vide la base de test avant chaque test
    use RefreshDatabase;

    public function test_admin_peut_lister_les_comptes(): void
    {
        // 1. Préparer : un admin et un gestionnaire
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->create(['role' => 'gestionnaire']);

        // 2. Agir : demander la liste des comptes
        $response = $this->actingAs($admin)->getJson('/api/utilisateurs');

        // 3. Vérifier : la liste contient les 2 comptes, sans mot de passe
        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonMissingPath('data.0.password');
    }

    public function test_gestionnaire_ne_peut_pas_lister_les_comptes(): void
    {
        // 1. Préparer : un gestionnaire
        $user = User::factory()->create(['role' => 'gestionnaire']);

        // 2. Agir : demander la liste des comptes
        $response = $this->actingAs($user)->getJson('/api/utilisateurs');

        // 3. Vérifier : l'accès est refusé
        $response->assertStatus(403);
    }

    public function test_admin_peut_creer_un_gestionnaire(): void
    {
        // 1. Préparer : un admin
        $admin = User::factory()->create(['role' => 'admin']);

        // 2. Agir : créer un gestionnaire
        $response = $this->actingAs($admin)->postJson('/api/utilisateurs', [
            'name' => 'Nadia Kettani',
            'email' => 'nadia@gca.test',
            'password' => 'motdepasse',
            'password_confirmation' => 'motdepasse',
        ]);

        // 3. Vérifier : le compte est créé avec le rôle gestionnaire et un mot de passe haché
        $response->assertStatus(201);
        $response->assertJsonPath('data.role', 'gestionnaire');
        $nouveau = User::where('email', 'nadia@gca.test')->first();
        $this->assertNotNull($nouveau);
        $this->assertTrue(Hash::check('motdepasse', $nouveau->password));
    }

    public function test_le_role_envoye_est_ignore(): void
    {
        // 1. Préparer : un admin
        $admin = User::factory()->create(['role' => 'admin']);

        // 2. Agir : essayer de créer un compte en envoyant role = admin
        $this->actingAs($admin)->postJson('/api/utilisateurs', [
            'name' => 'Pirate',
            'email' => 'pirate@gca.test',
            'password' => 'motdepasse',
            'password_confirmation' => 'motdepasse',
            'role' => 'admin',
        ]);

        // 3. Vérifier : le compte créé est quand même un gestionnaire
        $this->assertSame('gestionnaire', User::where('email', 'pirate@gca.test')->first()->role);
    }

    public function test_gestionnaire_ne_peut_pas_creer_un_compte(): void
    {
        // 1. Préparer : un gestionnaire
        $user = User::factory()->create(['role' => 'gestionnaire']);

        // 2. Agir : essayer de créer un compte
        $response = $this->actingAs($user)->postJson('/api/utilisateurs', [
            'name' => 'Test',
            'email' => 'test@gca.test',
            'password' => 'motdepasse',
            'password_confirmation' => 'motdepasse',
        ]);

        // 3. Vérifier : la création est interdite
        $response->assertStatus(403);
    }

    public function test_creation_refusee_si_email_deja_utilise_ou_mot_de_passe_non_confirme(): void
    {
        // 1. Préparer : un admin et un compte avec un email déjà pris
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->create(['email' => 'deja@gca.test']);

        // 2. Agir : envoyer un email déjà pris et une confirmation différente
        $response = $this->actingAs($admin)->postJson('/api/utilisateurs', [
            'name' => 'Test',
            'email' => 'deja@gca.test',
            'password' => 'motdepasse',
            'password_confirmation' => 'autrechose',
        ]);

        // 3. Vérifier : erreurs de validation sur email et password
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email', 'password']);
    }

    public function test_compte_en_ligne_si_jeton_utilise_recemment(): void
    {
        // 1. Préparer : un admin, un gestionnaire actif il y a 2 minutes, un autre il y a 2 heures
        $admin = User::factory()->create(['role' => 'admin', 'name' => 'A admin']);
        $actif = User::factory()->create(['role' => 'gestionnaire', 'name' => 'B actif']);
        $absent = User::factory()->create(['role' => 'gestionnaire', 'name' => 'C absent']);
        $actif->createToken('api_token')->accessToken->forceFill(['last_used_at' => now()->subMinutes(2)])->save();
        $absent->createToken('api_token')->accessToken->forceFill(['last_used_at' => now()->subHours(2)])->save();

        // 2. Agir : demander la liste des comptes
        $response = $this->actingAs($admin)->getJson('/api/utilisateurs');

        // 3. Vérifier : seul le gestionnaire récent est « en ligne »
        // (tri par rôle puis par nom : A admin, B actif, C absent)
        $response->assertJsonPath('data.1.name', 'B actif');
        $response->assertJsonPath('data.1.en_ligne', true);
        $response->assertJsonPath('data.2.name', 'C absent');
        $response->assertJsonPath('data.2.en_ligne', false);
    }
}
