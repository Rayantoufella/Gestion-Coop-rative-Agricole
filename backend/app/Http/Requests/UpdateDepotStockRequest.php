<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Requête de modification d'un dépôt de stock existant.
 */
class UpdateDepotStockRequest extends FormRequest
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
     * Règles de validation pour la modification d'un dépôt de stock.
     *
     * Tous les champs sont facultatifs (sometimes) :
     * - membre_id : doit exister dans la table membres.
     * - produit_id : doit exister dans la table produits.
     * - quantite : numérique, supérieure ou égale à 0.
     * - date_depot : date valide.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'membre_id' => ['sometimes', 'exists:membres,id'],
            'produit_id' => ['sometimes', 'exists:produits,id'],
            'quantite' => ['sometimes', 'numeric', 'min:0'],
            'date_depot' => ['sometimes', 'date'],
        ];
    }
}
