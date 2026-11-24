<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    // Vide la base de test avant chaque test
    use RefreshDatabase;

    public function test_connexion_reussie_renvoie_un_token(): void
    {
        // 1. Préparer : créer un utilisateur (mot de passe "password" par défaut)
        $user = User::factory()->create();

        // 2. Agir : appeler l'API comme le ferait React
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        // 3. Vérifier : 200 et un token dans la réponse
        $response->assertStatus(200)
                 ->assertJsonStructure(['token', 'user']);
    }

    public function test_mauvais_mot_de_passe_renvoie_401(): void
    {
        // 1. Préparer
        $user = User::factory()->create();

        // 2. Agir : envoyer un mauvais mot de passe
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'faux',
        ]);

        // 3. Vérifier : 401 et le bon message
        $response->assertStatus(401)
                 ->assertJson(['message' => 'Email ou mot de passe incorrect']);
    }
}