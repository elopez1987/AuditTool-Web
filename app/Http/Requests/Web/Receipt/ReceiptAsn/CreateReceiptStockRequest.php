<?php

declare(strict_types=1);

namespace App\Http\Requests\Web\Receipt\ReceiptAsn;

use Illuminate\Foundation\Http\FormRequest;

class CreateReceiptStockRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'prtnum' => 'required|string|exists:inventorypartnumbers,prtnum',
            'rotary' => ['required', 'numeric', 'regex:/^\d{1,14}(?:\.\d{1,4})?$/'],
            'definitive' => ['required', 'numeric', 'regex:/^\d{1,14}(?:\.\d{1,4})?$/'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
