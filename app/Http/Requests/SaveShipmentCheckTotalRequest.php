<?php

namespace App\Http\Requests;

use App\Traits\Api\General\ErrorResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class SaveShipmentCheckTotalRequest extends FormRequest
{
    use ErrorResponse;
    public function rules(): array
    {
        return [

            'shipmentid' => ['required', 'exists:orderdeliveries,shipmentid'],
            'locationid' => ['required', 'exists:storagelocations,barcode'],
            'quantity' => ['required', 'integer'],
            'accessories' => ['nullable'],
            'changes' => ['nullable'],
            'startprocess' => ['nullable'],
            'finishprocess' => ['nullable'],
            'value01' => ['nullable'],
            'value02' => ['nullable'],
            'value03' => ['nullable'],
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
