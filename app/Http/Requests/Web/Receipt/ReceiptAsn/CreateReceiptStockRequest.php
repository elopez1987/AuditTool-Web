<?php

namespace App\Http\Requests\Web\Receipt\ReceiptAsn;

use Illuminate\Foundation\Http\FormRequest;

class CreateReceiptStockRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'prtnum' => 'required|string|exists:inventorypartnumbers,prtnum',
            'quantity' => 'required|integer|min:1',
            'productiondate' => ['required', 'date'],
            'batch' => ['required', 'string', 'max:30'],
            'rotary' => ['required', 'int'],
            'definitive' => ['required', 'int'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
