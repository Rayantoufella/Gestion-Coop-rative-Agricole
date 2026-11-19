<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Requête de modification d'un compte gestionnaire.
 */
class UpdateUtilisateurRequest extends FormRequest
{
    /**
     * Autoriser la requête.
     *
     * Le droit est vérifié par la Policy (UserPolicy), qui autorise
     * seulement l'administrateur à modifier un gestionnaire.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation pour la modification d'un gestionnaire.
     *
     * - name : obligatoire, chaîne de caractères, 255 caractères maximum.
     * - email : obligatoire, adresse e-mail valide, unique dans la table users
     *   en ignorant le compte en cours de modification.
     * - password : facultatif, 8 caractères minimum, doit être confirmé par
     *   le champ password_confirmation.
     *
     * Le rôle n'est pas modifiable.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->route('utilisateur')),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];
    }
}
