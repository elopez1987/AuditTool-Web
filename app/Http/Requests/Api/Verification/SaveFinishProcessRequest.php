<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Verification;

use Illuminate\Foundation\Http\FormRequest;

class SaveFinishProcessRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'AsnId' => ['required', 'string', 'exists:receiptasn,uuid_asn'],
            'ConfirmShortages' => ['sometimes', 'boolean'],
            'ShortageReason' => ['nullable', 'required_if:ConfirmShortages,true', 'string', 'max:1000'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
