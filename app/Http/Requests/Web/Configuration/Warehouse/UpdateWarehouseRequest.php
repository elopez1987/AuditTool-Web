<?php

namespace App\Http\Requests\Web\Configuration\Warehouse;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWarehouseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // 'centerid' is intentionally not validated/updated: it is
            // read-only in the form and excluded from the update, same as
            // the original controller behavior.
            'name' => 'required|string|max:50',
            'statusid' => 'required|string|max:20',
            'auxiliary01' => 'nullable|string|max:100',
            'auxiliary02' => 'nullable|string|max:100',
            'auxiliary03' => 'nullable|string|max:100',
            'auxiliary04' => 'nullable|string|max:100',
            'auxiliary05' => 'nullable|string|max:100',
            'auxiliary06' => 'nullable|string|max:100',
            'auxiliary07' => 'nullable|string|max:100',
            'auxiliary08' => 'nullable|string|max:100',
            'center' => 'nullable|string|max:30',
            'warehouse' => 'nullable|string|max:30',
            'file' => 'nullable|image|max:5120',
        ];
    }
}
