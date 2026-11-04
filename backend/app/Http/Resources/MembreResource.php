<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MembreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
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
