<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à effectuer cette requête.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Modifier si nécessaire pour gérer les permissions
    }

    /**
     * Règles de validation pour la requête.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'deadline' => 'required|date',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
            'status' => 'required|string|in:pending,processing,completed,cancelled', // Validation pour le statut
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
            'client_id.required' => 'Le client est obligatoire.',
            'client_id.exists' => 'Le client sélectionné n’existe pas.',
            'deadline.required' => 'La date limite est obligatoire.',
            'deadline.date' => 'La date limite doit être une date valide.',
            'product_ids.required' => 'Au moins un produit doit être sélectionné.',
            'product_ids.array' => 'Les produits doivent être fournis sous forme de tableau.',
            'product_ids.*.exists' => 'Un ou plusieurs produits sélectionnés n’existent pas.',
            'status.required' => 'Le statut de la commande est obligatoire.',
            'status.string' => 'Le statut doit être une chaîne de caractères.',
            'status.in' => 'Le statut sélectionné est invalide. Valeurs acceptées : pending, processing, completed, cancelled.',
        ];
    }
}
