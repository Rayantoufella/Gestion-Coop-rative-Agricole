<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Représentation JSON d'un acheteur.
 *
 * Expose l'identifiant, le nom et le téléphone de l'acheteur
 * afin d'alimenter les ventes groupées.
 */
class AcheteurResource extends JsonResource
{
    /**
     * Transformer un acheteur en tableau JSON.
     *
     * Seuls les champs publics de l'acheteur sont exposés, sans
     * transformation de date ni relation conditionnelle.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'telephone' => $this->telephone,
        ];
    }
}
