<?php

namespace App\Http\Requests\Web\Yard\RefuseUnit;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRefuseUnitRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'folionumber' => 'required|numeric|max_digits:20|exists:receiptunits,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
