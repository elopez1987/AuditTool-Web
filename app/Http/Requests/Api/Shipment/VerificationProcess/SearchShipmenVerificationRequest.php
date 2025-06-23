<?php

namespace App\Http\Requests\Api\Shipment\VerificationProcess;

use App\Traits\Api\General\ErrorResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class SearchShipmenVerificationRequest extends FormRequest
{
    use ErrorResponse;
    public function rules(): array
    {
        return [
            'shipmentid' => 'required|string|exists:orderdeliveries,shipmentid',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator,'getDataShipmentCheck');
    }
}
