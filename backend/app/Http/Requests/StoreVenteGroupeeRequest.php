<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Requête de création d'une vente groupée.
 */
class StoreVenteGroupeeRequest extends FormRequest
{
    /**
     * Autoriser la requête.
     *
     * Le droit est vérifié par la Policy (VenteGroupeePolicy), qui autorise
     * les rôles admin et gestionnaire.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Le droit est vérifié par la Policy.
        return true;
    }

    /**
     * Règles de validation pour la création d'une vente groupée.
     *
     * - produit_id : obligatoire, doit exister dans la table produits.
     * - acheteur_id : obligatoire, doit exister dans la table acheteurs.
     * - date_vente : obligatoire, date valide.
     * - quantite : obligatoire, numérique, supérieure ou égale à 0,01.
     * - prix_unitaire : obligatoire, numérique, supérieur ou égal à 0,01.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'produit_id' => ['required', 'exists:produits,id'],
            'acheteur_id' => ['required', 'exists:acheteurs,id'],
            'date_vente' => ['required', 'date'],
            'quantite' => ['required', 'numeric', 'min:0.01'],
            'prix_unitaire' => ['required', 'numeric', 'min:0.01'],
        ];
    }
}
