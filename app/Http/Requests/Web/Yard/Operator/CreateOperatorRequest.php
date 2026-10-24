<?php

namespace App\Http\Requests\Web\Yard\Operator;

use Illuminate\Foundation\Http\FormRequest;

class CreateOperatorRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'centerid' => 'required|exists:systemcenterdistributions,centerid',
			'barcode' => ['required','unique:storageoperators,barcode'],
            'name' => 'required|string|max:70',
            'drivernumber' => 'nullable|max:100',
            'phone' => 'nullable|numeric|max_digits:10',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
