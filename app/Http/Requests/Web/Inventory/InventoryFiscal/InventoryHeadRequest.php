<?php

namespace App\Http\Requests\Web\Inventory\InventoryFiscal;

use Illuminate\Foundation\Http\FormRequest;

class InventoryHeadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'centerid' => ['required'],
            'warehouseid' => ['required'],
            'name' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
