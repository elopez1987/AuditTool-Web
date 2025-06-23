<?php

namespace App\Http\Requests\Maintenance;

use App\Traits\Api\General\ErrorResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StorageUsageDataForkLiftRequest extends FormRequest
{
    use ErrorResponse;

    public function rules(): array
    {
        return [
            'serialnumber' => ['required', 'exists:storagelifttrucks'],
            'horometro' => ['required'],
            'distance' => ['required'],
            'stationary' => ['required'],
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
        $this->errorResponse($validator,'saveEvidenceCheckListTruck');
    }

    
}
