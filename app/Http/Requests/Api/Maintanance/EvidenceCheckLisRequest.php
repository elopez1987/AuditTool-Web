<?php

namespace App\Http\Requests\Api\Maintanance;

use App\Rules\Base64;
use App\Traits\Api\General\ErrorResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class EvidenceCheckLisRequest extends FormRequest
{
    use ErrorResponse;

    public function rules(): array
    {
        return [
            'checklistid' => 'required|exists:checklistlifttrucks,id',
            'file' => ['required',new Base64()]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'checklistid.required' => 'El valor de check list es obligatorio',
            'file.required' => 'La foto de evidencia es obligatoria.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator,'saveEvidenceCheckListTruck');
    }


}
