<?php

namespace App\Http\Requests\Web\Shipments\OrderDelivery;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'centerid' => 'required|string|max:15|exists:systemcenterdistributions,centerid',
            'warehouseid' => 'required|string|max:15|exists:systemwarehouses,warehouseid',
            'shipmentid' => 'required|unique:orderdeliveries,shipmentid|regex:/^[a-zA-Z0-9]+$/',
            'auxiliary03' => 'nullable|regex:/^[a-zA-Z0-9]+$/',
            'workday' => 'required|date',
            'turnid' => 'required|exists:storageturns,id',
            'carrierid' => 'required|exists:shipmentcarriers,id',
            'customerid' => 'required|exists:ordercustomers,id',
            'shipmenttypeid' => 'required|exists:shipmenttypes,id',
            'loadingdate' => 'required',
            'downloaddate' => 'required',
        ];
    }
}
