<?php

namespace App\Http\Requests\Api\Shipment\VerificationProcess;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class SearchStatusPickRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'shipmentid' => 'required|string|exists:orderdeliveries,shipmentid'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        return $this->errorResponse($validator, 'getStatusPickForShipment');
    }



}
