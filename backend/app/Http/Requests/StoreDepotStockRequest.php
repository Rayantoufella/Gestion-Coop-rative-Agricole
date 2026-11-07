<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreDepotStockRequest extends FormRequest
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
            'membre_id' => ['required', 'exists:membres,id'],
            'produit_id' => ['required', 'exists:produits,id'],
            'quantite' => ['required', 'numeric', 'min:0'],
            'date_depot' => ['required', 'date'],
        ];
    }
}
