<?php

namespace App\Http\Requests\Web\Yard\Truck;

use Illuminate\Foundation\Http\FormRequest;

class CreateTruckRequest extends FormRequest
{
    public function rules()
    {
        return [
            'centerid' => 'required|exists:systemcenterdistributions,centerid',
            'warehouseid' => 'required|exists:systemwarehouses,warehouseid',
            'carrierid' => 'required|exists:shipmentcarriers,id',
            'trucknumber' => 'required|string|max:30',
            'operatorname' => 'required|string|max:50',
            'barcodetruck' => 'required|numeric|min_digits:4|max_digits:20',
            'barcodeoperator' => 'required|numeric|min_digits:4|max_digits:20',
            'drivernumber' => 'required|max:50',
            'phone' => 'required|max_digits:10',
            'econumber' => 'required|max:20',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
