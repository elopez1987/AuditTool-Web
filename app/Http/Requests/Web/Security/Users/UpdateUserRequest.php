<?php

namespace App\Http\Requests\Web\Security\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'password' => ['required', 'string', Password::min(8)->mixedCase()
                ->uncompromised()->symbols()->letters()->numbers()],
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
            'name.max' => 'El campo nombre es mayor a 50 caracteres.',

            'username.required' => 'El campo usuario es requerido.',
            'username.string' => 'El campo usuaario debe ser una cadena.',
            'username.unique' => 'El campo usuario ya se encuentra registrado.',

            'password.min' => 'El campo contraseña debe tener al menos 8 caracteres.',
            'password.required' => 'El campo contraseña es requerido.',
            'password.string' => 'El campo contraseña debe ser una cadena.',
            'password.symbols' => 'El campo contraseña debe contener al menos un caracter.',
            'password.numbers' => 'El campo contraseña debe contener al menos un número.',
            'password.mixed' => 'El campo contraseña debe contener letras y números.',
            'password.letters' => 'El campo contraseña debe contener letras.',
        ];
    }

}
