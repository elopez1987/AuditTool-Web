<?php

namespace App\Http\Requests\Api\Shipment\VerificationProcess;

use App\Rules\PartnumberOrBarcodeExists;
use App\Traits\Api\General\ErrorResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class SaveProcessCountRequest extends FormRequest
{
    use ErrorResponse;

    public function rules(): array
    {
        return [
            'shipmentid' => 'required|string|max:20|exists:orderdeliveries,shipmentid',
            'lpnid' => 'required|string',
            'partnumberid' => ['required', new PartnumberOrBarcodeExists()],
            'quantity' => 'required|integer|min:1',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages()
    {
        return [
            'partnumberid.exists' => 'Partnumber Not Found',
            'partnumberid.required' => 'Partnumber Not Found',
            'partnumberid.string' => 'Partnumber Not Found',
            'partnumberid.min' => 'Partnumber Not Found',
            'partnumberid.max' => 'Partnumber Not Found',
            'quantity.required' => 'Quantity Not Found',
            'quantity.integer' => 'Quantity Not Found',
            'quantity.min' => 'Quantity Not Found',
            'quantity.integer' => 'Quantity Not Found',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        return $this->errorResponse($validator, 'saveCheckShipment');
    }

}
