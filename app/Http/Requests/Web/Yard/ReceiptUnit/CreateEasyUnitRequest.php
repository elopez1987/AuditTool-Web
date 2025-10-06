<?php

namespace App\Http\Requests\Web\Yard\ReceiptUnit;

use Illuminate\Foundation\Http\FormRequest;

class CreateEasyUnitRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'warehouseid' => ['required', 'exists:systemwarehouses,warehouseid'],
            'shipmentid' => ['required', 'max:20'],
            'carrierid' => ['required', 'exists:shipmentcarriers,id'],
            'truckid' => ['required', 'exists:shipmenttrucks,id'],
            'operatorname' => ['required'],
            'boxnumber' => ['required', 'max:50'],
            'ecobox' => ['required', 'max:50'],
            'trucknumber' => ['required', 'max:50'],
            'econumber' => ['required', 'max:20'],
            'containerseal' => ['nullable', 'max:50'],
            'phone' => ['nullable', 'max:10'],
            'zoneid' => ['nullable', 'exists:storagezones,id'],
            'locationid' => ['nullable', 'exists:storagelocations,id'],
            'razonstatusid' => ['required', 'exists:storagereasons,id'],
            'unittypeid' => ['required', 'exists:storageunittypes,id'],
            'truckcategoryid' => ['required', 'exists:shipmenttruckcategories,truckcategoryid'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
