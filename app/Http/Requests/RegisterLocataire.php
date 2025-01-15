<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterLocataire extends FormRequest
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
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'tel' => 'required|string|max:15',
            //'role' => 'required|in:proprietaire,locataire',
            'email' => 'required|string|email|max:255|unique:users',
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
            'nom.required' => 'le nom est obligatoire',
            'email.required' => 'une adresse est obligatoire',
            'email.unique' => 'cette adresse éxiste déja',
            'role.in' => 'Le champ rôle doit être soit proprietaire soit locataire',
            //'password.required' => 'un mot de passe est obligatoire',
            'prenom.required' => 'le prenom est obligatoire',
            'tel.required' => 'le numero de telephone est obligatoire',
            'role.required' => 'le role est obligatoire',
            'email.email' => 'l adresse email doit être valide',
        ];
    }
}
