<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class InventoryCardCountRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'inventory_card_id' => ['required', 'array', 'min:1'],
            'inventory_card_id.*' => ['required', 'distinct', 'exists:inventory_cards,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
