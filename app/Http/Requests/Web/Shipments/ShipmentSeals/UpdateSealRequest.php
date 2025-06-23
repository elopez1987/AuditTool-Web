<?php

namespace App\Http\Requests\Web\Shipments\ShipmentSeals;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSealRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'receivername' => 'required|string',
            'containerseal01' =>  'required|numeric'
        ];
    }
}
