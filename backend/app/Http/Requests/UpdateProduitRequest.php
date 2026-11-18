<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Requête de modification d'un produit existant.
 */
class UpdateProduitRequest extends FormRequest
{
    /**
     * Autoriser la requête.
     *
     * Le droit est vérifié par la Policy (ProduitPolicy), qui autorise
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
     * Règles de validation pour la modification d'un produit.
     *
     * - nom : obligatoire, chaîne de caractères, 255 caractères maximum.
     * - categorie : obligatoire, chaîne de caractères, 100 caractères maximum.
     * - unite : obligatoire, chaîne de caractères, 20 caractères maximum.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'categorie' => ['required', 'string', 'max:100'],
            'unite' => ['required', 'string', 'max:20'],
        ];
    }
}
