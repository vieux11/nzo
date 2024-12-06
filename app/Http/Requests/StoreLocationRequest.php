<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreLocationRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'description' => 'nullable|string|max:255',
            'loyer_montant' => 'required|numeric|min:0',
            'id_propriete' => 'required|exists:proprietes,id',
            'id_locataire' => 'required|exists:locataires,id',
            'devise' => 'required|in:FC,USD'
        ];
    }
    public function failedvalidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'success' => false,
            'status_code'=> 422,
            'error' =>true,
            'message' =>'erreur de validation',
            'inputReceived' => $this->all(),
            'errorList' => $validator->errors()
        ], 422));
    }
    public function messages()
    {
        return [
            'loyer_montant.required' => 'Le montant du loyer est obligatoire.',
            'id_propriete.required' => 'Une propriété doit être sélectionnée.',
            'id_locataire.required' => 'Un locataire doit être sélectionné.',
            'id_propriete.exists' => 'La propriété spécifiée n’existe pas.',
            'id_locataire.exists' => 'Le locataire spécifié n’existe pas.',
            'devise.in' => 'La devise choisie est invalide. Veuillez sélectionner soit FC, soit USD.'
        ];
    }

}
