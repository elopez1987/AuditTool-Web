<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class InventoryAisleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'centerid' => ['required', 'exists:systemcenterdistributions,centerid'],
            'warehouseid' => ['required', 'exists:systemwarehouses,warehouseid'],
            'name' => ['required'],
            'configurationflag' => ['nullable'],
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
