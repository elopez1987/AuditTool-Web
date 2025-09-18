<?php

namespace App\Http\Requests\Web\Security\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdatePasswordUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'current_password' => 'required|string|current_password',
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(12)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
            'password_confirmation' => 'required|string|same:password',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El campo nombre es requerido.',
            'name.string' => 'El campo nombre debe ser un string.',
            'name.max' => 'El campo nombre no puede exceder 100 caracteres.',
            'email.required' => 'El campo email es requerido.',
            'email.email' => 'El formato del email no es válido.',
            'email.max' => 'El email no puede exceder 255 caracteres.',
            'current_password.required' => 'Debe ingresar su contraseña actual.',
            'current_password.current_password' => 'La contraseña actual no es correcta.',
            'password.required' => 'La nueva contraseña es requerida.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
            'password.min' => 'La contraseña debe tener al menos 12 caracteres.',
            'password_confirmation.required' => 'Debe confirmar la nueva contraseña.',
            'password_confirmation.same' => 'La confirmación debe coincidir con la nueva contraseña.',
        ];
    }

}
