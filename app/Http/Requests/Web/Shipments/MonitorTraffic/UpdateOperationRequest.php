<?php

namespace App\Http\Requests\Web\Shipments\MonitorTraffic;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOperationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'supervisor' => 'nullable|string|max:100',
            'checker' => 'nullable|string|max:100',
            'forklift' => 'nullable|string|max:100',
            'auxiliary01' => 'nullable|string|max:100',
            'auxiliary02' => 'nullable|string|max:100',
            'auxiliary03' => 'nullable|string|max:100',
            'auxiliary04' => 'nullable|string|max:100',
            'auxiliary05' => 'nullable|string|max:100',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
