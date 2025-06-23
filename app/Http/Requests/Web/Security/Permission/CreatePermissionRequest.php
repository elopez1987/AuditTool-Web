<?php

namespace App\Http\Requests\Web\Security\Permission;

use Illuminate\Foundation\Http\FormRequest;

class CreatePermissionRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:15|unique:permissions,name|regex:/^[a-zA-Z0-9]+$/',
            'functionid' => 'required|exists:securityfunctions,functionid',
            'moduleid' => 'required|exists:securitymodules,moduleid',
            'description' => 'required|string|max:150',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'name' => str_replace(' ', '', $this->name),
        ]);
    }
    
}
