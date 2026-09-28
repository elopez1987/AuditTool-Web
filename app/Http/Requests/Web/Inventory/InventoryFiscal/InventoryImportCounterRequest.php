<?php

namespace App\Http\Requests\Web\Inventory\InventoryFiscal;

use Illuminate\Foundation\Http\FormRequest;

class InventoryImportCounterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:xlsx,xls', 'max:10240'],
            'inventoryid' => ['required', 'integer', 'exists:inventory_heads,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
