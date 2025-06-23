<?php

namespace App\Http\Requests\Web\Yard\LabelTruck;

use Illuminate\Foundation\Http\FormRequest;

class RegisterLabelRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'centerid' => 'required|exists:systemcenterdistributions,centerid',
            'warehouseid' => 'required|exists:systemwarehouses,warehouseid',
            'typeid' => 'required',
            'boxnumber' => 'required',
            'econumber' => 'required',
            'carrierid' => 'required|exists:shipmentcarriers,id',
            'truckid' => 'required_if:typeid,BX'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
