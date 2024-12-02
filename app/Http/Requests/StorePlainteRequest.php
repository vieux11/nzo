<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePlainteRequest extends FormRequest
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
            'sujet' => 'required|string|max:255',
            'description' => 'required|string',
        ];
    }
    /**
     * Personnalisez la gestion des erreurs en cas de validation échouée.
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'status_code' => 422,
            'message' => 'Erreur de validation des données.',
            'errorList' => $validator->errors(),
        ], 422));
    }
    public function messages(): array
    {
        return [
            'sujet.required' => 'Le champ sujet est obligatoire.',
            'sujet.string' => 'Le champ sujet doit être une chaîne de caractères.',
            'sujet.max' => 'Le champ sujet ne doit pas dépasser 255 caractères.',
            'description.required' => 'Le champ description est obligatoire.',
            'description.string' => 'Le champ description doit être une chaîne de caractères.',
        ];
    }
}
