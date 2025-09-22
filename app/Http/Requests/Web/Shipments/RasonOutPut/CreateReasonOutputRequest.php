<?php

namespace App\Http\Requests\Web\Shipments\RasonOutPut;

use Illuminate\Foundation\Http\FormRequest;

class CreateReasonOutputRequest extends FormRequest
{
    public function rules()
    {
        return [
            'centerid' => 'required',
            'description' => 'required|string|max:50',
            'type' => 'required|max:3|string',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
