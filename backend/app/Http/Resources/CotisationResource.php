<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CotisationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
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
