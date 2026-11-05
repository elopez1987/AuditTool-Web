<?php

namespace App\Http\Requests\Web\Security\Funciones;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CreateFunction extends FormRequest
{
    public function rules(): array
    {
        return [
            'moduleid' => 'required|integer|exists:securitymodules,moduleid',
            'description' => 'required|string|max:100',
        ];
    }

    public function authorize(): bool
    {
        return Gate::allows('securityfunctions.create');
    }
}
