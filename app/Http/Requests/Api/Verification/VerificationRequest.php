<?php

namespace App\Http\Requests\Api\Verification;

use App\Traits\Api\ApiValidationResponse;
use Illuminate\Foundation\Http\FormRequest;

class VerificationRequest extends FormRequest
{
    use ApiValidationResponse;

    public function rules(): array
    {
        return [
            'AsnId' => ['required', 'string', 'exists:receiptasn,uuid_asn'],
            'PartNumberCode' => ['required', 'string', 'exists:inventorypartnumbers,prtnum'],
            'Quantity' => ['required', 'integer', 'min:1'],
            'TypeId' => ['required', 'string', 'exists:systemstatus,statusid'],
            'UnitsMeasureId' => ['required', 'integer', 'exists:inventoryunitsmeasure,id'],
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
