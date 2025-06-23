<?php

namespace App\Http\Requests\Api\Inverse;

use App\Traits\Api\General\ErrorResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class SaveCheckRequest extends FormRequest
{
    use ErrorResponse;
    public function rules(): array
    {
        return [
            'shipmentid' => 'required|exists:orderdeliveries,shipmentid',
            'deliveryid' => 'required|string',
            'partnumberid' => 'required|exists:inventorypartnumbers,prtnum',
            'quantity' => 'required|numeric|min:1',
            'unitmeasureid' => 'required|string',
            'statusid' => 'required|string',
            'locationid' => 'required|string',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator,'saveCheckReceiptInverse');
    }
}
