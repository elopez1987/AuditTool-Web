<?php

namespace App\Http\Requests\Web\Inventory\InventoryFiscal;

use Illuminate\Foundation\Http\FormRequest;

class InventoryAdjustLocationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'partnumber01' => ['nullable', 'exists:inventorypartnumbers,prtnum'],
            'quantity01' => ['required', 'integer'],
            'partnumber02' => ['nullable', 'exists:inventorypartnumbers,prtnum'],
            'quantity02' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'partnumber01.exists' => 'El número de parte 01 no existe.',
            'quantity01.required' => 'La cantidad es obligatoria.',
            'quantity01.integer' => 'La cantidad 01 debe ser un número entero.',
            'partnumber02.exists' => 'El número de parte 02 no existe.',
            'quantity02.required' => 'La cantidad es obligatoria.',
            'quantity02.integer' => 'La cantidad 02 debe ser un número entero.',
        ];
    }
}
