<?php

namespace App\Http\Requests\Web\Yard\Operator;

use Illuminate\Foundation\Http\FormRequest;

class CreateOperatorRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:70',
            'phone' => 'required|numeric|max_digits:10',
            'drivernumber' => 'required|max:255',
            'carrierid' => 'required|exists:shipmentcarriers,id',
            'centerid' => 'required|exists:systemcenterdistributions,centerid',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
