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
//            'email' => 'required',
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
        ];
    }

}
