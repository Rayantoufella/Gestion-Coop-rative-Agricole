<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreMembreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
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
