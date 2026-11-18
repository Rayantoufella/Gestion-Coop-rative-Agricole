<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Requête de création d'un acheteur.
 */
class StoreAcheteurRequest extends FormRequest
{
    /**
     * Autoriser la requête.
     *
     * Le droit est vérifié par la Policy (AcheteurPolicy), qui autorise
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
     * Règles de validation pour la création d'un acheteur.
     *
     * - nom : obligatoire, chaîne de caractères, 255 caractères maximum.
     * - telephone : obligatoire, chaîne de caractères, 20 caractères maximum.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:20'],
        ];
    }
}
