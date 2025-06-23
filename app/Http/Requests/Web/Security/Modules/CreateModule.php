<?php

namespace App\Http\Requests\Web\Security\Modules;

use Illuminate\Foundation\Http\FormRequest;

class CreateModule extends FormRequest
{
    public function rules()
    {
        return [
            'description' => 'required|string|max:100|unique:securitymodules,description',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
