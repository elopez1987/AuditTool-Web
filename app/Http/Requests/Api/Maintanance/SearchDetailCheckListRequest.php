<?php

namespace App\Http\Requests\Api\Maintanance;

use App\Traits\Api\General\ErrorResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class SearchDetailCheckListRequest extends FormRequest
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

    protected function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator,'getDataLiftTruck');
    }
}
