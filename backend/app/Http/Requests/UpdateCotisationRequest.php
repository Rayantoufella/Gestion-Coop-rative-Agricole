<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Requête de modification d'une cotisation existante.
 */
class UpdateCotisationRequest extends FormRequest
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
     * Règles de validation pour la modification d'une cotisation.
     *
     * Tous les champs sont facultatifs (sometimes) :
     * - membre_id : doit exister dans la table membres.
     * - periode : chaîne de caractères, 255 caractères maximum.
     * - montant : numérique, supérieur ou égal à 0.
     * - date_paiement : date valide.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'membre_id' => ['sometimes', 'exists:membres,id'],
            'periode' => ['sometimes', 'string', 'max:255'],
            'montant' => ['sometimes', 'numeric', 'min:0'],
            'date_paiement' => ['sometimes', 'date'],
        ];
    }
}
