<?php

namespace App\Http\Requests\Api\Verification;

use App\Traits\Api\ApiValidationResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VerificationRequest extends FormRequest
{
    use ApiValidationResponse;

    public function rules(): array
    {
        return [
            'AsnId' => ['required', 'string', 'exists:receiptasn,uuid_asn'],
            'PartNumberCode' => ['required', 'string', 'exists:inventorypartnumbers,prtnum'],
            'Quantity' => ['required', 'integer', 'min:1'],
            'TypeId' => ['required', 'string', Rule::in(['TRP-01', 'TRP-02'])],
            'ProductionDate' => ['nullable', 'date', 'date_format:Y-m-d'],
            'ExpireDate' => ['nullable', 'date', 'date_format:Y-m-d'],
            'ExpireCode' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
