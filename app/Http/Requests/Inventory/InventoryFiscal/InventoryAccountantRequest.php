<?php

namespace App\Http\Requests\Inventory\InventoryFiscal;

use Illuminate\Foundation\Http\FormRequest;

class InventoryAccountantRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            //			'inventory_head_id' => ['required', 'exists:inventory_heads,id'],
            //			'inventory_supervisor_id' => ['nullable', 'exists:inventory_supervisors,id'],
            'user_id' => ['required', 'exists:users,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
