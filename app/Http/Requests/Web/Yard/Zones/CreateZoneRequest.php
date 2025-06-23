<?php

namespace App\Http\Requests\Web\Yard\Zones;

use Illuminate\Foundation\Http\FormRequest;

class CreateZoneRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'centerid' => 'required|exists:systemcenterdistributions,centerid',
            'warehouseid' => 'required|exists:systemwarehouses,warehouseid',
            'name' => 'required|string|max:50',
            'zoneid' => 'required|string|max:15',
            'auxiliary01' => 'required|string|max:190',
            'capacity' => 'required|integer',
        ];
    }
}
