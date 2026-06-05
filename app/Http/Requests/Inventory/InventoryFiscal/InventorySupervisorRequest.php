<?php

namespace App\Http\Requests\Inventory\InventoryFiscal;

use Illuminate\Foundation\Http\FormRequest;

class InventorySupervisorRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
