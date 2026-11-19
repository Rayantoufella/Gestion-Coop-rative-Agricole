<?php

namespace App\Http\Requests;

use App\Models\Membre;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

/**
 * Requête de création d'une cotisation pour un membre.
 */
class StoreCotisationRequest extends FormRequest
{
    /**
     * Déterminer si l'utilisateur est autorisé à effectuer cette requête.
     *
     * Seul le rôle admin est autorisé.
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
     * - date_paiement : obligatoire, date valide, supérieure ou égale à la
     *   date d'adhésion du membre.
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

    /**
     * Vérifier que la date de paiement n'est pas antérieure à l'adhésion.
     *
     * Ce contrôle est exécuté après les règles de base, lorsque le membre et
     * la date de paiement sont valides.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $membre = Membre::find($this->input('membre_id'));
            $datePaiement = $this->date('date_paiement');

            if ($membre && $datePaiement->lt($membre->date_adhesion)) {
                $validator->errors()->add(
                    'date_paiement',
                    "La date de paiement doit être supérieure ou égale à la date d'adhésion du membre."
                );
            }
        });
    }
}
