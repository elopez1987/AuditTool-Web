<?php

namespace App\Http\Requests\Api\Shipment\VerificationProcess;

use App\Rules\PartnumberOrBarcodeExists;
use App\Traits\Api\General\ErrorResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class SearchSkuRequest extends FormRequest
{
    use ErrorResponse;

    public function rules(): array
    {
        return [
            'shipmentid' => 'required|string|exists:orderdeliveries,shipmentid',
            'partnumberid' => ['required', 'string', new PartnumberOrBarcodeExists()],
            'lpnid' => 'required|string'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        return $this->errorResponse($validator, 'getSkuForShipmentCheck');
    }


}
