<?php

namespace App\Traits\Web\Shipments\ShipmentTransport;

use App\Traits\Api\General\ErrorResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class SearchLpnRequest extends FormRequest
{
    use ErrorResponse;

    public function rules(): array
    {
        return [
            'lpnid' => 'required|string|exists:shipmentstock,lpnid',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages()
    {
        return [
            'lpnid.required' => 'El número de Lpn es requerido.',
            'lpnid.reexistsquired' => 'El número de Lpn no existe.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator, 'printlabellpncheck');
    }
}
