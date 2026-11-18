<?php

namespace App\Http\Requests\Web\Configuration\Warehouse;

use Illuminate\Foundation\Http\FormRequest;

class StoreWarehouseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'centerid' => 'required|string|max:15',
            'warehouseid' => 'required|string|max:15|unique:systemwarehouses,warehouseid',
            'name' => 'required|string|max:50',
			'center' => ['nullable','string','max:30'],
			'warehouse' => ['nullable','string','max:30'],
			'auxiliary01' => ['nullable','string'],
			'auxiliary02' => ['nullable','string'],
			'auxiliary03' => ['nullable','string'],
			'auxiliary04' => ['nullable','string'],
			'auxiliary05' => ['nullable','string'],
			'auxiliary06' => ['nullable','string'],
			'auxiliary07' => ['nullable','string'],
			'auxiliary08' => ['nullable','string'],
        ];
    }
}
