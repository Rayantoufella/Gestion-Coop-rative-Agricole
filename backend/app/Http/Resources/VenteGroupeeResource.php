<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VenteGroupeeResource extends JsonResource
{
    /**
     * Transformer une vente groupée en tableau JSON.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'produit_id' => $this->produit_id,
            'acheteur_id' => $this->acheteur_id,
            'date_vente' => $this->date_vente?->format('Y-m-d'),
            'quantite' => $this->quantite,
            'prix_unitaire' => $this->prix_unitaire,
            'montant_total' => $this->quantite * $this->prix_unitaire,
            'produit' => new ProduitResource($this->whenLoaded('produit')),
            'acheteur' => new AcheteurResource($this->whenLoaded('acheteur')),
        ];
    }
}
