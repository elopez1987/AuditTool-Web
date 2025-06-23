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
            'email' => 'required',
//            'password3' => ['required', 'string', Password::min(8)->mixedCase()
//                ->uncompromised()->symbols()->letters()->numbers()],
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

            'auxiliary02.required' => 'El campo celular es requerido.',
            'auxiliary02.numeric' => 'El campo celular debe ser un númerico.',
            'auxiliary02.max' => 'El campo celular es mayor a 10 caracteres.',

            'password3.min' => 'El campo contraseña debe tener al menos 8 caracteres.',
            'password3.required' => 'El campo contraseña es requerido.',
            'password3.string' => 'El campo contraseña debe ser una cadena.',
            'password3.symbols' => 'El campo contraseña debe contener al menos un caracter.',
            'password3.numbers' => 'El campo contraseña debe contener al menos un número.',
            'password3.mixed' => 'El campo contraseña debe contener letras y números.',
            'password3.letters' => 'El campo contraseña debe contener letras.',
        ];
    }

}
