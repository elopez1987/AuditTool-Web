<?php

namespace App\Http\Requests\Web\Shipments\ShipmentTransport;

use Illuminate\Foundation\Http\FormRequest;

class RegisterTransportRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'operatorname' => 'required|string|max:100',
            'boxnumber' => 'required|string|max:20',
            'trucknumber' => 'required|string|max:20',
            'phone' => 'required|numeric|max_digits:10',
            'drivernumber' => 'required|string|max:30',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
