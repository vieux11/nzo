<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreProprieteRequest extends FormRequest
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
                'adresse' => 'required|string|max:255',
                'description' => 'nullable|string'
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
        ]));
    }
    public function messages()
    {
        return [
            'adresse.required' => 'L’adresse est obligatoire.',
            'adresse.string' => 'L’adresse doit être une chaîne de caractères.'
        ];
    }
}
