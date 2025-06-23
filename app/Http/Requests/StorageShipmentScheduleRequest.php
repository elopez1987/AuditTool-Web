<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorageShipmentScheduleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'centerid' => 'required|exists:systemcenterdistributions,centerid',
            'warehouseid' => 'required|exists:systemwarehouses,warehouseid',
            'carrierid' => 'required|exists:shipmentcarriers,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
