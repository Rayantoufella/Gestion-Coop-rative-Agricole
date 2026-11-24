<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Représentation JSON d'une vente groupée.
 *
 * Expose le produit vendu, l'acheteur, la date de vente, la quantité, le
 * prix unitaire et le montant total calculé de la transaction.
 */
class VenteGroupeeResource extends JsonResource
{
    /**
     * Transformer une vente groupée en tableau JSON.
     *
     * La date de vente est formatée en `Y-m-d` (null si absente). Le
     * `montant_total` est calculé à la volée en multipliant la quantité
     * par le prix unitaire. Les relations `produit` et `acheteur` ne sont
     * incluses que si elles ont été préalablement chargées via `whenLoaded`.
     *
     * @param  Request  $request
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
