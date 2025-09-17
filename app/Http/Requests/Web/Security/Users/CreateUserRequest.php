<?php

namespace App\Http\Requests\Web\Security\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class CreateUserRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
			'name' => 'required|string|max:150',
            'email' => 'required|email|unique:users,email',
			'password' => ['required', 'string', Password::min(10)->mixedCase()
                ->uncompromised()->symbols()->letters()->numbers()],
            'role_id' => 'required|exists:roles,name',
			'username' => 'required|max:8|string|unique:users,username',
			'auxiliary01' => 'nullable|string|max:190|regex:/^[a-zA-Z0-9\s]+$/',
			'auxiliary02' => 'nullable|string|max:190|regex:/^[a-zA-Z0-9\s]+$/',
			'auxiliary03' => 'nullable|string|max:190|regex:/^[a-zA-Z0-9\s]+$/',
			'auxiliary04' => 'nullable|string|max:190|regex:/^[a-zA-Z0-9\s]+$/',
			'auxiliary05' => 'nullable|string|max:190|regex:/^[a-zA-Z0-9\s]+$/',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El campo nombre es requerido.',
            'name.string' => 'El campo nombre debe ser un string.',
            'name.max' => 'El campo nombre es mayor a 50 caracteres.',

            'email.required' => 'El campo correo es requerido.',
            'email.email' => 'El campo correo debe ser un correo electronico.',
            'email.unique' => 'El campo correo ya se encuentra registrado.',


            'role_id.exists' => 'El campo rol no existe.',
            'role_id.required' => 'El campo rol es requerido.',

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
