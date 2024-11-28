<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'product_type_id' => 'required|exists:product_types,id', // Validation pour la clé étrangère
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Le nom du produit est obligatoire.',
            'name.string' => 'Le nom du produit doit être une chaîne de caractères.',
            'name.max' => 'Le nom du produit ne peut pas dépasser 255 caractères.',
            'description.string' => 'La description doit être une chaîne de caractères.',
            'price.required' => 'Le prix est obligatoire.',
            'price.numeric' => 'Le prix doit être un nombre.',
            'price.min' => 'Le prix doit être au moins égal à 0.',
            'product_type_id.required' => 'Le type de produit est obligatoire.',
            'product_type_id.exists' => 'Le type de produit sélectionné n\'existe pas.',
        ];
    }
}
