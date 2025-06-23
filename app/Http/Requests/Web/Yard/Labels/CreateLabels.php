<?php

namespace App\Http\Requests\Web\Yard\Labels;

use Illuminate\Foundation\Http\FormRequest;

class CreateLabels extends FormRequest
{
    public function rules()
    {
        return [
            'centerid' => 'required',
            'description' => 'required|string'
        ];
    }

    public function authorize()
    {
        return true;
    }
}
