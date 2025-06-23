<?php

namespace App\Http\Requests\Api\Maintanance;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class SaveKeySupervisorRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file' => 'required',
            'checklistid' => 'required',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages()
    {
        return [
          'file.required' => 'El archivo es obligatorio.',
          'serialnumber' => 'El número de serie es obligatorio.'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator,'saveKeySupervisor');
    }
}
