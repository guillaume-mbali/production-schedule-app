<?php

namespace App\Http\Requests\Admin;

use App\Models\ProductType;
use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;

class ProductTypeRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à effectuer cette requête.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Obtenez les règles de validation qui s'appliquent à la requête.
     *
     * @return array
     */

    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('product_types', 'name')->ignore($this->route('productType')),
            ],
            'production_speed' => 'required|numeric|min:0',
            'changeover_times' => 'nullable|array',
            'changeover_times.*' => 'nullable|integer|min:0',
        ];
    }




    /**
     * Messages personnalisés pour les erreurs de validation.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Le nom est obligatoire.',
            'name.string' => 'Le nom doit être une chaîne de caractères.',
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'name.unique' => 'Ce nom est déjà utilisé pour un autre type de produit.',
            'production_speed.required' => 'La vitesse de production est obligatoire.',
            'production_speed.numeric' => 'La vitesse de production doit être un nombre.',
            'production_speed.min' => 'La vitesse de production doit être au moins de 0.',
            'changeover_time.required' => 'Le temps de changement est obligatoire pour les types de produit existants.',
            'changeover_time.numeric' => 'Le temps de changement doit être un nombre.',
            'changeover_time.min' => 'Le temps de changement doit être au moins de 0.',
        ];
    }
}
