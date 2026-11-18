<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Requête de création d'un membre.
 */
class StoreMembreRequest extends FormRequest
{
    /**
     * Déterminer si l'utilisateur est autorisé à effectuer cette requête.
     *
     * Le droit est vérifié par la Policy (MembrePolicy) : la création est
     * réservée au rôle admin.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation pour la création d'un membre.
     *
     * - code_membre : obligatoire, chaîne de caractères, 50 caractères maximum,
     *   unique dans la table membres.
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
                'code_membre'   => ['required', 'string', 'max:50', 'unique:membres,code_membre'],
                'nom'           => ['required', 'string', 'max:255'],
                'prenom'        => ['required', 'string', 'max:255'],
                'telephone'     => ['required', 'string', 'max:20'],
                'date_adhesion' => ['required', 'date'],
                'statut'        => ['required', 'in:actif,inactif'],
            ];
    }
}
