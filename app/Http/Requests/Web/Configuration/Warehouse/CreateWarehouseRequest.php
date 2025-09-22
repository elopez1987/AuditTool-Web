<?php

namespace App\Http\Requests\Web\Configuration\Warehouse;

use Illuminate\Foundation\Http\FormRequest;

class CreateWarehouseRequest extends FormRequest
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
        ];
    }
}
