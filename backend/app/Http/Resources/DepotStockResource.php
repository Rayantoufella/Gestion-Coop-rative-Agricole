<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepotStockResource extends JsonResource
{
    /**
     * Transformer un dépôt de stock en tableau JSON.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'membre_id' => $this->membre_id,
            'produit_id' => $this->produit_id,
            'date_depot' => $this->date_depot?->format('Y-m-d'),
            'quantite' => $this->quantite,
            'membre' => new MembreResource($this->whenLoaded('membre')),
            'produit' => new ProduitResource($this->whenLoaded('produit')),
        ];
    }
}
