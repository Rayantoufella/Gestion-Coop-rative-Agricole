<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Requête de modification d'un membre existant.
 */
class UpdateMembreRequest extends FormRequest
{
    /**
     * Déterminer si l'utilisateur est autorisé à effectuer cette requête.
     *
     * Le droit est vérifié par la Policy (MembrePolicy) : la modification est
     * réservée au rôle admin.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation pour la modification d'un membre.
     *
     * - code_membre : obligatoire, chaîne de caractères, 50 caractères maximum,
     *   unique dans la table membres en ignorant le membre en cours de modification.
     * - nom : obligatoire, chaîne de caractères, 255 caractères maximum.
     * - prenom : obligatoire, chaîne de caractères, 255 caractères maximum.
     * - telephone : obligatoire, chaîne de caractères, 20 caractères maximum.
     * - date_adhesion : obligatoire, date valide.
     * - statut : obligatoire, doit valoir actif ou inactif.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code_membre' => ['required', 'string', 'max:50', Rule::unique('membres', 'code_membre')->ignore($this->route('membre'))],
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:20'],
            'date_adhesion' => ['required', 'date'],
            'statut' => ['required', 'in:actif,inactif'],
        ];
    }
}
