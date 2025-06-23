<?php

namespace App\Http\Requests;

use App\Traits\Api\General\ErrorResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoragechecklocationbridgRequest extends FormRequest
{
    use ErrorResponse;
    public function rules(): array
    {
        return [

            'shipmentid' => ['required', 'exists:orderdeliveries,shipmentid'],
            'turnid' => ['required', 'integer'],
            'locationid' => ['required', 'integer'],
            'complemento' => ['nullable', 'integer'],
            'totalIzquierdo' => ['nullable', 'integer'],
            'totalderecho' => ['nullable', 'integer'],
            'totalfinal' => ['nullable', 'integer'],
            'detalleIzquierdo' => ['nullable'],
            'detalleDerecho' => ['nullable'],
            'namesupervisor' => ['nullable'],
            'keysupervisor' => ['nullable'],
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
        $this->errorResponse($validator,'savevalidacionshipmentbrid');
    }



}
