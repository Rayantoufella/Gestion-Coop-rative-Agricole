<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Requête de création d'un compte gestionnaire.
 */
class StoreUtilisateurRequest extends FormRequest
{
    /**
     * Autoriser la requête.
     *
     * Le droit est vérifié par la Policy (UserPolicy), qui autorise
     * seulement l'administrateur.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Le droit est vérifié par la Policy.
        return true;
    }

    /**
     * Règles de validation pour la création d'un gestionnaire.
     *
     * - name : obligatoire, chaîne de caractères, 255 caractères maximum.
     * - email : obligatoire, adresse e-mail valide, unique dans la table users.
     * - password : obligatoire, 8 caractères minimum, doit être confirmé
     *   par le champ password_confirmation.
     *
     * Le rôle n'est pas envoyé par le formulaire : il est toujours
     * « gestionnaire » (fixé dans le contrôleur).
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
}
