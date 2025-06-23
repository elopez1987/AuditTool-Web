<?php

namespace App\Http\Requests\Web\Shipments\ShipmentType;

use Illuminate\Foundation\Http\FormRequest;

class CreateShipmentTypeRequest extends FormRequest
{
    public function rules()
    {
        return [
            'centerid' => 'required|string|max:15|exists:systemcenterdistributions,centerid',
            'name' => 'required|string|max:50',
            'auxiliary01' => 'nullable|string|max:190',
            'auxiliary02' => 'nullable|string|max:190',
            'auxiliary03' => 'nullable|string|max:190',
            'auxiliary04' => 'nullable|string|max:190',
            'auxiliary05' => 'nullable|string|max:190',
            'configurationflag' => 'nullable|string|max:190',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
