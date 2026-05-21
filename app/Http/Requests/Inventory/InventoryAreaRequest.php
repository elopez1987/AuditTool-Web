<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class InventoryAreaRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'centerid' => ['required', 'exists:systemcenterdistributions'],
            'warehouseid' => ['required', 'exists:systemwarehouses'],
            'name' => ['required'],
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
