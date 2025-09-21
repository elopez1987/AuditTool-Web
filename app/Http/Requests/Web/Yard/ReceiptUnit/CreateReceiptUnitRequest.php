<?php

namespace App\Http\Requests\Web\Yard\ReceiptUnit;

use Illuminate\Foundation\Http\FormRequest;

class CreateReceiptUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'centerid' => 'required|string|exists:systemcenterdistributions,centerid',
            'warehouseid' => 'required|string|exists:systemwarehouses,warehouseid',
            'razonstatusid' => 'required|exists:storagereasons,id',
            'unitstatusid' => 'required|exists:storageunitstatus,id',
            'shipmentid' => 'required|max:20',
            'containerseal' => 'nullable|string',
            'providerid' => 'nullable|exists:receiptproviders,id',
            'zoneid' => 'required|exists:storagezones,id',
            'locationid' => 'required|exists:storagelocations,id',
            'barcodeoperator' => 'required|string|min:4|max:10',
            'operatorname' => 'required|string',
            'drivernumber' => 'required|string|max:20',
            'phone' => 'required|numeric',
            'barcodebox' => 'required|string|min:4|max:10',
            'boxnumber' => 'required',
            'ecobox' => 'required',
            'carrierid' => 'required|exists:shipmentcarriers,id',
            'unittypeid' => 'required|exists:storageunittypes,id',
            'truckcategoryid' => 'required|exists:shipmenttruckcategories,truckcategoryid',
            'truckid' => 'required|exists:shipmenttrucks,id',
            'barcodetruck' => 'required|string|min:4|max:10',
            'trucknumber' => 'required',
            'econumber' => 'required',
            'comment' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'barcodeoperator.min_digits' => 'Se requieren 4 digitos para el codigo de operador.',
            'barcodeoperator.max_digits' => 'Se permite un maximo de 10 digitos para el codigo de operador.',
        ];
    }
}
