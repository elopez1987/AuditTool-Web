<?php

namespace App\Http\Requests\Web\Security\Funciones;

use Illuminate\Foundation\Http\FormRequest;

class CreateFunction extends FormRequest
{
    public function rules()
    {
        return [
            'moduleid' => 'required',
            'description' => 'required|string|max:100',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
