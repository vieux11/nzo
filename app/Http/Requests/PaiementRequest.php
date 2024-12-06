<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;


class PaiementRequest extends FormRequest
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
            //
            'methode_paiement' => 'required|string|in:mobile_money,carte_bancaire,virement,espèces',
            'mois' => 'required_if:is_first,true|integer|between:1,12',
            'annee' => 'required_if:is_first,true|integer|min:2000',
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
        return[
            'methode_paiement.required' => 'Le moyen de paiement est obligatoire.',
            'annee.min' => 'L\'année doit être au moins 2000.',
            'mois.between' => 'Le mois doit être compris entre 1 et 12.',
            'mois.integer' =>'le mois doit être un entier',
            'annee.integer' =>'L\'année doit être un entier',
            'methode_paiement.in' => 'Le moyen de paiement doit être l\'un des suivants : mobile_money, carte_bancaire, virement, espèces.',
            'mois.required_if' => 'Le mois est obligatoire pour un premier paiement.',
            'annee.required_if' => 'L\'année est obligatoire pour un premier paiement.',
        ];
    }
}

