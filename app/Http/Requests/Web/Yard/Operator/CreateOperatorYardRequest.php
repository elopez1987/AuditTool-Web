<?php

namespace App\Http\Requests\Web\Yard\Operator;

use Illuminate\Foundation\Http\FormRequest;

class CreateOperatorYardRequest extends FormRequest
{
    public function rules()
    {
        return [
            'centerid' => 'required',
            'name' => 'required|string',
            'numberphone' => 'required|numeric'
        ];
    }

    public function authorize()
    {
        return true;
    }
}
