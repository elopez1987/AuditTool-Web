<?php

namespace App\Http\Requests\Api\Shipment\OrderDelivery;

use App\Traits\Api\General\ErrorResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class SearchShipmentInPlanRequest extends FormRequest
{
    use ErrorResponse;
    public function rules(): array
    {
        return [
            'shipmentid' => 'required|string|max:30|exists:orderdeliveries,shipmentid',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }


    protected function failedValidation(Validator $validator)
    {
        return $this->errorResponse($validator,'getDetailShipment');
    }


}
