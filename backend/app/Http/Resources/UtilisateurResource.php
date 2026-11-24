<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * Représentation JSON d'un compte utilisateur.
 *
 * Le mot de passe n'est jamais exposé. On ajoute la date de dernière
 * activité (dernière utilisation d'un jeton Sanctum) et un indicateur
 * « en ligne » si cette activité date de moins de 5 minutes.
 */
class UtilisateurResource extends JsonResource
{
    /**
     * Transformer un utilisateur en tableau JSON.
     *
     * Le champ tokens_max_last_used_at est calculé dans le contrôleur
     * avec withMax('tokens', 'last_used_at').
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $derniereActivite = $this->tokens_max_last_used_at
            ? Carbon::parse($this->tokens_max_last_used_at)
            : null;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'created_at' => $this->created_at?->format('Y-m-d'),
            'derniere_activite' => $derniereActivite?->format('Y-m-d H:i'),
            'en_ligne' => $derniereActivite !== null
                && $derniereActivite->greaterThanOrEqualTo(now()->subMinutes(5)),
        ];
    }
}
