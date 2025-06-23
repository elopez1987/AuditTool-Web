<?php

namespace App\Http\Requests\Api\Inverse;

use App\Traits\Api\General\ErrorResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class SaveEvidenceInverseRequest extends FormRequest
{
    use ErrorResponse;
    public function rules(): array
    {
        return [
            'shipmentid' => 'required|string|exists:orderdeliveries,shipmentid',
//            'file' => ['required', new Base64()],
            'file' => 'required'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator,'getDataLiftTruck');
    }

}
