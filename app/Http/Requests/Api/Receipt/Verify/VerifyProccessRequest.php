<?php

namespace App\Http\Requests\Api\Receipt\Verify;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class VerifyProccessRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'shipmentid' => 'required|exists:receiptasn,shipmentid',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'shipmentid.required' => 'El campo embarque es obligatorio.',
            'shipmentid.exists' => 'El número de embarque no existe.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        $response = response()->json([
            'ServiceName' => 'verifyProcess',
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
