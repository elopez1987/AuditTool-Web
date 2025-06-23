<?php

namespace App\Http\Requests\Api\Yard\ReceiptUnit;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SaveUnitPlantRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'barcodebox' => 'required|exists:storageaccesswarehouse,auxiliary01',
            'shipment' => 'required|string|max:20',
            'seal' => 'required|string|max:30',
//            'operatorname' => 'required|string|max:100',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        $response = response()->json([
            'ServiceName' => 'generateNewRegisterForReceiptQr',
            'ProcessDate' => Carbon::now()->format('Y-m-d H:m:s'),
            'Code' => '-1',
            'Status' => 'Error',
            'Errors' => $errors,
            'Message' => $errors->first(),
            'Data' => [],
        ], 422);
        throw new HttpResponseException($response);
    }

}
