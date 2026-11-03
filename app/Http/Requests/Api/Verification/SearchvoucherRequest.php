<?php

namespace App\Http\Requests\Api\Verification;

use Illuminate\Foundation\Http\FormRequest;

class SearchvoucherRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'VoucherId' => ['required', 'string', 'max:30', 'exists:receiptasn,shipmentid'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
