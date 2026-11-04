<?php

namespace App\Http\Requests\Api\Verification;

use Illuminate\Foundation\Http\FormRequest;

class saveFinishProcessRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'AsnId' => ['required', 'string', 'exists:receiptasn,uuid_asn'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
