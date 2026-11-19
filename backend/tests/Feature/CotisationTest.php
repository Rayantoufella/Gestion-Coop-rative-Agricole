<?php

namespace Tests\Feature;

use App\Models\Cotisation;
use App\Models\Membre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CotisationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_peut_creer_une_cotisation_a_la_date_d_adhesion(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $membre = Membre::factory()->create(['date_adhesion' => '2026-01-15']);

        $response = $this->actingAs($admin)->postJson('/api/cotisations', [
            'membre_id' => $membre->id,
            'periode' => '01/2026',
            'montant' => 500,
            'date_paiement' => '2026-01-15',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('cotisations', [
            'membre_id' => $membre->id,
            'date_paiement' => '2026-01-15',
        ]);
    }

    public function test_admin_ne_peut_pas_creer_une_cotisation_avant_l_adhesion(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $membre = Membre::factory()->create(['date_adhesion' => '2026-01-15']);

        $response = $this->actingAs($admin)->postJson('/api/cotisations', [
            'membre_id' => $membre->id,
            'periode' => '01/2026',
            'montant' => 500,
            'date_paiement' => '2026-01-14',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('date_paiement');
        $this->assertDatabaseCount('cotisations', 0);
    }

    public function test_admin_peut_modifier_un_autre_champ_sans_toucher_aux_dates(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $membre = Membre::factory()->create(['date_adhesion' => '2026-01-15']);
        $cotisation = Cotisation::factory()->create([
            'membre_id' => $membre->id,
            'date_paiement' => '2026-01-20',
        ]);

        $response = $this->actingAs($admin)->putJson("/api/cotisations/{$cotisation->id}", [
            'periode' => '02/2026',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('data.periode', '02/2026');
    }

    public function test_admin_ne_peut_pas_modifier_la_date_de_paiement_avant_l_adhesion(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $membre = Membre::factory()->create(['date_adhesion' => '2026-01-15']);
        $cotisation = Cotisation::factory()->create([
            'membre_id' => $membre->id,
            'date_paiement' => '2026-01-20',
        ]);

        $response = $this->actingAs($admin)->putJson("/api/cotisations/{$cotisation->id}", [
            'date_paiement' => '2026-01-14',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('date_paiement');
    }

    public function test_admin_ne_peut_pas_deplacer_une_cotisation_vers_un_membre_plus_recent(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $membre = Membre::factory()->create(['date_adhesion' => '2026-01-15']);
        $autreMembre = Membre::factory()->create(['date_adhesion' => '2026-01-25']);
        $cotisation = Cotisation::factory()->create([
            'membre_id' => $membre->id,
            'date_paiement' => '2026-01-20',
        ]);

        $response = $this->actingAs($admin)->putJson("/api/cotisations/{$cotisation->id}", [
            'membre_id' => $autreMembre->id,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('date_paiement');
    }

    public function test_admin_peut_deplacer_une_cotisation_si_la_date_reste_valide(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $membre = Membre::factory()->create(['date_adhesion' => '2026-01-15']);
        $autreMembre = Membre::factory()->create(['date_adhesion' => '2026-01-10']);
        $cotisation = Cotisation::factory()->create([
            'membre_id' => $membre->id,
            'date_paiement' => '2026-01-20',
        ]);

        $response = $this->actingAs($admin)->putJson("/api/cotisations/{$cotisation->id}", [
            'membre_id' => $autreMembre->id,
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('data.membre_id', $autreMembre->id);
    }

    public function test_admin_ne_peut_pas_decaler_la_date_adhesion_apres_une_cotisation(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $membre = Membre::factory()->create(['date_adhesion' => '2026-01-15']);
        Cotisation::factory()->create([
            'membre_id' => $membre->id,
            'date_paiement' => '2026-01-20',
        ]);

        $response = $this->actingAs($admin)->putJson("/api/membres/{$membre->id}", [
            'code_membre' => $membre->code_membre,
            'nom' => $membre->nom,
            'prenom' => $membre->prenom,
            'telephone' => $membre->telephone,
            'date_adhesion' => '2026-01-25',
            'statut' => $membre->statut,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('date_adhesion');
    }

    public function test_admin_peut_decaler_la_date_adhesion_avant_les_cotisations(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $membre = Membre::factory()->create(['date_adhesion' => '2026-01-15']);
        Cotisation::factory()->create([
            'membre_id' => $membre->id,
            'date_paiement' => '2026-01-20',
        ]);

        $response = $this->actingAs($admin)->putJson("/api/membres/{$membre->id}", [
            'code_membre' => $membre->code_membre,
            'nom' => $membre->nom,
            'prenom' => $membre->prenom,
            'telephone' => $membre->telephone,
            'date_adhesion' => '2026-01-10',
            'statut' => $membre->statut,
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('data.date_adhesion', '2026-01-10');
    }
}
