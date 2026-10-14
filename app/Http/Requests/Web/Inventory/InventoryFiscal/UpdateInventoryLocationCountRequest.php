<?php

namespace App\Http\Requests\Web\Inventory\InventoryFiscal;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInventoryLocationCountRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'partnumber01' => ['required', 'string', 'exists:inventorypartnumbers,prtnum'],
            'quantity01' => ['required', 'integer', 'min:0'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'partnumber01.required' => 'El número de parte es obligatorio.',
            'partnumber01.exists' => 'El número de parte no existe.',
            'quantity01.required' => 'La cantidad es obligatoria.',
            'quantity01.integer' => 'La cantidad debe ser un número entero.',
            'quantity01.min' => 'La cantidad no puede ser negativa.',
        ];
    }
}
