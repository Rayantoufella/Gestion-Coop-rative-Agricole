<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Requête de création d'une cotisation pour un membre.
 */
class StoreCotisationRequest extends FormRequest
{
    /**
     * Déterminer si l'utilisateur est autorisé à effectuer cette requête.
     *
     * Seul le rôle admin est autorisé.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    /**
     * Règles de validation pour la création d'une cotisation.
     *
     * - membre_id : obligatoire, doit exister dans la table membres.
     * - periode : obligatoire, chaîne de caractères, 255 caractères maximum.
     * - montant : obligatoire, numérique, supérieur ou égal à 0.
     * - date_paiement : obligatoire, date valide.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'membre_id' => ['required', 'exists:membres,id'],
            'periode' => ['required', 'string', 'max:255'],
            'montant' => ['required', 'numeric', 'min:0'],
            'date_paiement' => ['required', 'date'],
        ];
    }
}
