<?php

namespace App\Http\Requests\Web\Yard\ShipmentTruck;

use Illuminate\Foundation\Http\FormRequest;

class CreateTruckRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'centerid' => 'required|exists:systemcenterdistributions,centerid',
            'name' => 'required|string|max:50',
            'truckid' => 'required|string|max:20',
            'auxiliary01' => 'nullable|string|max:190',
            'auxiliary02' => 'nullable|string|max:190',
            'auxiliary03' => 'nullable|string|max:190',
            'auxiliary04' => 'nullable|string|max:190',
            'auxiliary05' => 'nullable|string|max:190',

        ];
    }
}
