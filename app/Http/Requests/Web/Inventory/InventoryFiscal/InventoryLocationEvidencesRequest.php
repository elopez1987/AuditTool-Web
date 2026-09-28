<?php

namespace App\Http\Requests\Web\Inventory\InventoryFiscal;

use Illuminate\Foundation\Http\FormRequest;

class InventoryLocationEvidencesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'inventory_head_id' => ['required', 'exists:inventory_heads'],
            'inventory_location_count_id' => ['required', 'exists:inventory_location_counts'],
            'path' => ['required'],
            'type' => ['nullable'],
            'user_id' => ['required', 'exists:users'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
