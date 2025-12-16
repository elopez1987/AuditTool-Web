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
            'productiondate' => 'required|date',
            'batch' => 'required',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
