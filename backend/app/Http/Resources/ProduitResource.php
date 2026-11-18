<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Représentation JSON d'un produit.
 *
 * Expose le nom, la catégorie et l'unité de mesure du produit afin
 * d'alimenter les dépôts de stock et les ventes groupées.
 */
class ProduitResource extends JsonResource
{
    /**
     * Transformer un produit en tableau JSON.
     *
     * Aucune date ni relation conditionnelle n'est exposée ; seuls les
     * champs descriptifs du produit sont renvoyés.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'categorie' => $this->categorie,
            'unite' => $this->unite,
        ];
    }
}
