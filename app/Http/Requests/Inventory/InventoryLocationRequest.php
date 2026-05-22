<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class InventoryLocationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'inventoryunitsmeasure_id' => ['required', 'exists:inventoryunitsmeasure,id'],
            'statusid' => ['nullable', 'exists:systemstatus,statusid'],
            'auxiliary01' => ['nullable'],
            'auxiliary02' => ['nullable'],
            'auxiliary03' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
