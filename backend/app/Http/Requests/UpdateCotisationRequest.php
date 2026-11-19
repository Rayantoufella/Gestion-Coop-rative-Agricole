<?php

namespace App\Http\Requests;

use App\Models\Membre;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

/**
 * Requête de modification d'une cotisation existante.
 */
class UpdateCotisationRequest extends FormRequest
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
     * Règles de validation pour la modification d'une cotisation.
     *
     * Tous les champs sont facultatifs (sometimes) :
     * - membre_id : doit exister dans la table membres.
     * - periode : chaîne de caractères, 255 caractères maximum.
     * - montant : numérique, supérieur ou égal à 0.
     * - date_paiement : date valide, supérieure ou égale à la date
     *   d'adhésion du membre.
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

    /**
     * Vérifier que la date effective de paiement respecte l'adhésion.
     *
     * En modification partielle, on utilise le nouveau membre et/ou la
     * nouvelle date s'ils sont envoyés, sinon les valeurs déjà enregistrées.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $cotisation = $this->route('cotisation');
            $membreId = $this->input('membre_id', $cotisation->membre_id);
            $datePaiement = $this->filled('date_paiement')
                ? $this->date('date_paiement')
                : $cotisation->date_paiement;
            $membre = Membre::find($membreId);

            if ($membre && $datePaiement->lt($membre->date_adhesion)) {
                $validator->errors()->add(
                    'date_paiement',
                    "La date de paiement doit être supérieure ou égale à la date d'adhésion du membre."
                );
            }
        });
    }
}
