<?php

namespace App\Http\Requests\Web\Security\Menus;

use Illuminate\Foundation\Http\FormRequest;

class CreateNewMenuRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'centerid' => 'required|string|exists:systemcenterdistributions,centerid',
            'menustrucid' => 'required|string|max:30|unique:securitymenustructure,menustrucid',
            'description' => 'required|string|max:150',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
