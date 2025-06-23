<?php

namespace App\Http\Requests\Web\Shipments\ShipmentPod;

use Illuminate\Foundation\Http\FormRequest;

class CreateShipmentPodRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'shipmentid' => 'required|string|max:20|exists:orderdeliveries,shipmentid',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }


}
