<?php

namespace App\Http\Requests\Web\Receipt\ReceiptAsn;

use App\Models\Yard\Carriers\ShipmentCarrier;
use Illuminate\Foundation\Http\FormRequest;

class CreateReceiptAsnRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        if (! $this->filled('carrierid')) {
            $defaultCarrier = ShipmentCarrier::whereRaw("charindex('SCR-04', configurationflag) > 0")
                ->first();
            if ($defaultCarrier) {
                $this->merge([
                    'carrierid' => $defaultCarrier->id,
                ]);
            }
        }
    }

    public function rules()
    {
        return [
            'centerid' => 'required|exists:systemcenterdistributions,centerid',
            'warehouseid' => 'required|exists:systemwarehouses,warehouseid',
            'providerid' => 'required|exists:receiptproviders,id',
            'shipmentid' => 'required|max:20|unique:receiptasn,shipmentid|regex:/^[a-zA-Z0-9]+$/',
            'addasndate' => 'required|date|after_or_equal:today',
            'carrierid' => 'nullable|exists:shipmentcarriers,id',
            'stockcategoryid' => 'required|exists:inventorystockcategories,id',
            'razonstatusid' => 'required|exists:storagereasons,id',
            'turnid' => 'required|exists:storageturns,id',
            'oc' => 'nullable|regex:/^[a-zA-Z0-9]+$/',
            'billoflading' => 'nullable|regex:/^[a-zA-Z0-9]+$/',
            'folionumber' => 'nullable|numeric:max_digits:20',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
