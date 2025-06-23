<?php

namespace App\Http\Requests\Api\Maintanance;

use App\Traits\Api\General\ErrorResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UnassignEquipmentRequest extends FormRequest
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

    public function messages()
    {
        return [
            'serialnumber.required' => 'El número de serie el obligatorio.',
            'serialnumber.exists' => 'No existen registros en el sistema.',
            'serialnumber.string' => 'El número de serie debe ser una cadena.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator, 'updateStatusToTruckAssign');
    }


}
