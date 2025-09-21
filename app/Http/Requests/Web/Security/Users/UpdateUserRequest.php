<?php

namespace App\Http\Requests\Web\Security\Users;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:150',
            'role_id' => 'required|exists:roles,name',
            'auxiliary01' => 'nullable|string|max:190|regex:/^[a-zA-Z0-9\s]+$/',
            'auxiliary02' => 'nullable|string|max:190|regex:/^[a-zA-Z0-9\s]+$/',
            'auxiliary03' => 'nullable|string|max:190|regex:/^[a-zA-Z0-9\s]+$/',
            'auxiliary04' => 'nullable|string|max:190|regex:/^[a-zA-Z0-9\s]+$/',
            'auxiliary05' => 'nullable|string|max:190|regex:/^[a-zA-Z0-9\s]+$/',
            'auth0' => 'nullable|string|max:190',

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
