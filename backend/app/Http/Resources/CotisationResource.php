<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Représentation JSON d'une cotisation.
 *
 * Expose la période, le montant et la date de paiement de la cotisation,
 * ainsi que le membre associé lorsqu'il a été chargé.
 */
class CotisationResource extends JsonResource
{
    /**
     * Transformer une cotisation en tableau JSON.
     *
     * La date de paiement est formatée en `Y-m-d` (null si absente) et le
     * membre n'est inclus que si la relation `membre` a été préalablement
     * chargée via `whenLoaded`.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'membre_id' => $this->membre_id,
            'periode' => $this->periode,
            'montant' => $this->montant,
            'date_paiement' => $this->date_paiement?->format('Y-m-d'),
            'membre' => new MembreResource($this->whenLoaded('membre')),
        ];
    }
}
