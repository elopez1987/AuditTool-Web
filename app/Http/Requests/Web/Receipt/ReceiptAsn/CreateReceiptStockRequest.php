<?php

namespace App\Http\Requests\Web\Receipt\ReceiptAsn;

use Illuminate\Foundation\Http\FormRequest;

class CreateReceiptStockRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'quantity' => 'required|integer|min:1',
            'productiondate' => 'required|date',
            'batch' => 'required',
            'prtnum' => 'required|string|exists:inventorypartnumbers,prtnum',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
