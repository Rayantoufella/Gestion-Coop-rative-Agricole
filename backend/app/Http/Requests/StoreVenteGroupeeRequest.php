<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreVenteGroupeeRequest extends FormRequest
{
    /**
     * Autoriser la requête.
     */
    public function authorize(): bool
    {
        // Le droit est vérifié par la Policy.
        return true;
    }

    /**
     * Règles de validation.
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
