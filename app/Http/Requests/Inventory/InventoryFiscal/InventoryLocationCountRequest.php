<?php

namespace App\Http\Requests\Inventory\InventoryFiscal;

use Illuminate\Foundation\Http\FormRequest;

class InventoryLocationCountRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'inventory_head_id' => ['required', 'exists:inventory_heads'],
            'inventory_aisle_id' => ['required', 'exists:inventory_aisles'],
            'inventory_card_id' => ['required', 'exists:inventory_cards'],
            'inventory_location_id' => ['required', 'exists:inventory_locations'],
            'inventorypartnumber01_id' => ['nullable', 'exists:inventorypartnumbers'],
            'quantity01' => ['nullable'],
            'inventorypartnumber02_id' => ['nullable', 'exists:inventorypartnumbers'],
            'quantity02' => ['nullable'],
            'user_count01_id' => ['nullable', 'exists:users'],
            'user_count02_id' => ['nullable', 'exists:users'],
            'statusid_count01' => ['nullable'],
            'statusid_count02' => ['nullable'],
            'statusid_location' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
