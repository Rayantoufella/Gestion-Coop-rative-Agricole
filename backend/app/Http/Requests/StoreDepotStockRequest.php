<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Requête de création d'un dépôt de stock.
 */
class StoreDepotStockRequest extends FormRequest
{
    /**
     * Autoriser la requête.
     *
     * Le droit est vérifié par la Policy (DepotStockPolicy), qui autorise
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
     * Règles de validation pour la création d'un dépôt de stock.
     *
     * - membre_id : obligatoire, doit exister dans la table membres.
     * - produit_id : obligatoire, doit exister dans la table produits.
     * - quantite : obligatoire, numérique, supérieure ou égale à 0.
     * - date_depot : obligatoire, date valide.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'membre_id' => ['required', 'exists:membres,id'],
            'produit_id' => ['required', 'exists:produits,id'],
            'quantite' => ['required', 'numeric', 'min:0'],
            'date_depot' => ['required', 'date'],
        ];
    }
}
