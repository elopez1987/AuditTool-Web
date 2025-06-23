<?php

namespace App\Http\Requests\Web\Shipments\ShipmentType;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShipmentTypeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:50',
            'auxiliary01' => 'nullable|string|max:190',
            'auxiliary02' => 'nullable|string|max:190',
            'auxiliary03' => 'nullable|string|max:190',
            'auxiliary04' => 'nullable|string|max:190',
            'auxiliary05' => 'nullable|string|max:190',
            'configurationflag' => 'nullable|string|max:190',
            'statusid' => 'required|string|max:15|exists:systemstatus,statusid',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
