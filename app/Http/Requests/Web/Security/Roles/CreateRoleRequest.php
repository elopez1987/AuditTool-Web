<?php

namespace App\Http\Requests\Web\Security\Roles;

use Illuminate\Foundation\Http\FormRequest;

class CreateRoleRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:roles,name|regex:/^[a-zA-Z0-9\s]+$/|max:15',
            'description' => 'required|string|max:99',
            'menustrucid' => 'required|string|exists:securitymenustructure,menustrucid',
            'centerid' => 'required|string|exists:systemcenterdistributions,centerid',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'name' => str_replace(' ', '', $this->name),
        ]);
    }

}
