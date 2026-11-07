<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDepotStockRequest extends FormRequest
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
            'membre_id' => ['sometimes', 'exists:membres,id'],
            'produit_id' => ['sometimes', 'exists:produits,id'],
            'quantite' => ['sometimes', 'numeric', 'min:0'],
            'date_depot' => ['sometimes', 'date'],
        ];
    }
}
