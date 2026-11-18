<?php

namespace App\Http\Requests\Api\Verification;

use Illuminate\Foundation\Http\FormRequest;

class SaveStartProcessRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'AsnId' => ['required', 'string', 'exists:receiptasn,uuid_asn'],
            'LocationId' => ['required', 'int', 'exists:storagelocations,id'],
            'Temperature' => [
                'required',
                'numeric',
                'between:-40,100',
                'decimal:0,2',
            ],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
