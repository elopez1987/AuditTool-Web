<?php

namespace App\Http\Requests\Web\Configuration\Warehouse;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWarehouseNotificationsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'parameter' => 'nullable|array',
            'parameter.*' => 'string',
        ];
    }
}
