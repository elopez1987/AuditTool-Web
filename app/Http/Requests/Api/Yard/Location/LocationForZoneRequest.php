<?php

namespace App\Http\Requests\Api\Yard\Location;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class LocationForZoneRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'zoneid' => 'required|numeric|exists:storagezones,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
    public function messages()
    {
        return [
            'zoneid.required' => 'El valor de la zona es obligatorio.',
            'zoneid.exists' => 'El valor de la zona no existe.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        $response = response()->json([
            'ServiceName' => 'getLocationForZone',
            'ProcessDate' => Carbon::now()->format('Y-m-d H:m:s'),
            'Code' => '-1',
            'Status'=>'Error',
            'Errors' => $errors,
            'Message' => $errors->first(),
            'Data' => [],
        ],422);
        throw new HttpResponseException($response);
    }
}
