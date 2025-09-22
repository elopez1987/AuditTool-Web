<?php

namespace App\Http\Requests\Web\Yard\Areas;

use Illuminate\Foundation\Http\FormRequest;

class StorageAreasRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'centerid' => ['required', 'exists:systemcenterdistributions,centerid'],
            'warehouseid' => ['required', 'exists:systemwarehouses,warehouseid'],
            'name' => ['required', 'max:100'],
            'auxiliary01' => ['nullable', 'max:100'],
            'auxiliary02' => ['nullable', 'max:100'],
            'auxiliary03' => ['nullable', 'max:100'],
            'statusid' => 'exists:systemstatus,statusid',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
