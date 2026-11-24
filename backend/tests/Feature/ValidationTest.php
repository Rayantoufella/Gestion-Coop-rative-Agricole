<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidationTest extends TestCase
{
    // Vide la base de test avant chaque test
    use RefreshDatabase;

    public function test_creation_membre_sans_donnees_renvoie_422(): void
    {
        // 1. Préparer : un utilisateur admin
        $user = User::factory()->create(['role' => 'admin']);

        // 2. Agir : envoyer un corps vide pour créer un membre
        $response = $this->actingAs($user)->postJson('/api/membres', []);

        // 3. Vérifier : la validation échoue sur les champs obligatoires
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['code_membre', 'nom', 'prenom']);
    }
}
