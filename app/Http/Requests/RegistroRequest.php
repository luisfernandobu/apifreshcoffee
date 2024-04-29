<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password as PasswordRules;

class RegistroRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required' ,'email', 'unique:users,email'],
            'password' => [
                'required',
                'confirmed',
                PasswordRules::min(8)->letters()->symbols()->numbers()
            ]
        ];
    }

    public function messages()
    {
        return [
            'name' => 'El campo Nombre es obligtorio.',
            'email.required' => 'El campo Email es obligtorio.',
            'email.email' => 'Email no válido.',
            'email.unique' => 'El email ingresado ya se encuentra registrado.',
            'password' => 'El password debe ser de al menos 8 caracteres y debe contener símbolos, números y letras.',
            'password.required' => 'El campo Password es obligatorio.',
            'password.confirmed' => 'Confirmación de password no coincide.'
        ];
    }
}
