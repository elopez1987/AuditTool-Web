<?php

namespace App\Http\Requests\Api\Maintanance;

use App\Rules\Base64;
use App\Traits\Api\General\ErrorResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class SaveCheckListMtnRequest extends FormRequest
{
    use ErrorResponse;
    public function rules(): array
    {
        return [
            'serialnumber' => 'required|string|exists:storagelifttrucks,serialnumber',
            'supervisorname' => 'required|string',
            'horometro' => 'required|string',
            'comment' => 'required|string',
            'checklistdetailid' => 'required|array',
            'file' => ['required',new Base64()]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages()
    {
        return [
            'file.required' => 'La firma del operador es requerida.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator,'saveCheckListLiftTruck');
    }
}
