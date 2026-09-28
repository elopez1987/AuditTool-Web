<?php

namespace App\Http\Requests\Web\Receipt\ReceiptAsn;

use Illuminate\Foundation\Http\FormRequest;

class ReceiptAsnTimeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'receiptid' => ['required', 'exists:receiptasn'],
            'send_to_billing' => ['nullable'],
            'start_billing' => ['nullable'],
            'finish_billing' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
