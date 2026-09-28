<?php

namespace App\Http\Requests\Web\Inventory\InventoryFiscal;

use Illuminate\Foundation\Http\FormRequest;

class InventoryAisleCountRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'inventory_aisle_id' => ['required', 'array', 'min:1'],
            'inventory_aisle_id.*' => ['required', 'distinct', 'exists:inventory_aisles,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
