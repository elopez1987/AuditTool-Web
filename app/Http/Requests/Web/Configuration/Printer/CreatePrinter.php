<?php

namespace App\Http\Requests\Web\Configuration\Printer;

use Illuminate\Foundation\Http\FormRequest;

class CreatePrinter extends FormRequest
{
    public function rules()
    {
        return [
            'centerid' => 'required',
            'description' => 'required|string|max:100',
            'ip' => 'required',
            'command' => 'required'
        ];
    }

    public function authorize()
    {
        return true;
    }
}
