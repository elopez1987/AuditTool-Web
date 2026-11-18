<?php

namespace App\Http\Requests\Web\Receipt\ReceiptAsn;

use Illuminate\Foundation\Http\FormRequest;

class CreateReceiptStockRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'prtnum' => 'required|string|exists:inventorypartnumbers,prtnum',
            'rotary' => ['required', 'int'],
            'definitive' => ['required', 'int'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
