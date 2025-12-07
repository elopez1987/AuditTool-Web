<?php

namespace App\Http\Requests\Web\Receipt\ReceiptAsn;

use Illuminate\Foundation\Http\FormRequest;

class CreateReceiptAsnRequest extends FormRequest
{
    public function rules()
    {
        return [
            'centerid' => 'required|exists:systemcenterdistributions,centerid',
            'warehouseid' => 'required|exists:systemwarehouses,warehouseid',
            'providerid' => 'required|exists:receiptproviders,id',
            'shipmentid' => 'required|max:20|unique:receiptasn,shipmentid|regex:/^[a-zA-Z0-9]+$/',
            'addasndate' => 'required|date|after_or_equal:today',
            'carrierid' => 'required|exists:shipmentcarriers,id',
            'stockcategoryid' => 'required|exists:inventorystockcategories,id',
            'razonstatusid' => 'required|exists:storagereasons,id',
            'turnid' => 'required|exists:storageturns,id',
            'oc' => 'nullable|regex:/^[a-zA-Z0-9]+$/',
            'billoflading' => 'nullable|regex:/^[a-zA-Z0-9]+$/',
            'folionumber' => 'nullable',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
