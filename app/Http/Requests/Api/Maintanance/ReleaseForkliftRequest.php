<?php

namespace App\Http\Requests\Api\Maintanance;

use App\Traits\Api\General\ErrorResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ReleaseForkliftRequest extends FormRequest
{
    use ErrorResponse;
    public function rules(): array
    {
        return [
            'serialnumber' => 'required|string|exists:storagelifttrucks,serialnumber',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'serialnumber.required' => 'The serial number field is required.',
            'serialnumber.string' => 'The serial number field must be a string.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator,'clearTruck');
    }



}
