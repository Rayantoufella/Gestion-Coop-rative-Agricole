<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Représentation JSON d'un membre.
 *
 * Expose l'identité du membre (code, nom, prénom, téléphone), sa date
 * d'adhésion et son statut.
 */
class MembreResource extends JsonResource
{
    /**
     * Transformer un membre en tableau JSON.
     *
     * La date d'adhésion est formatée en `Y-m-d`. Aucune relation
     * conditionnelle n'est exposée par cette ressource.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'code_membre'   => $this->code_membre,
            'nom'           => $this->nom,
            'prenom'        => $this->prenom,
            'telephone'     => $this->telephone,
            'date_adhesion' => $this->date_adhesion->format('Y-m-d'),
            'statut'        => $this->statut,
        ];
    }
}
