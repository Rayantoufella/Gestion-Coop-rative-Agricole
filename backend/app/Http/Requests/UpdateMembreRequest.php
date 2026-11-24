<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

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
     * - date_adhesion : obligatoire, date valide, non postérieure à une
     *   cotisation existante du membre.
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

    /**
     * Vérifier que la nouvelle date d'adhésion reste compatible avec les
     * cotisations déjà enregistrées pour ce membre.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $membre = $this->route('membre');
            $dateAdhesion = $this->date('date_adhesion');

            $cotisationAnterieure = $membre->cotisations()
                ->whereDate('date_paiement', '<', $dateAdhesion->toDateString())
                ->exists();

            if ($cotisationAnterieure) {
                $validator->errors()->add(
                    'date_adhesion',
                    "La date d'adhésion ne peut pas être postérieure à une cotisation existante."
                );
            }
        });
    }
}
