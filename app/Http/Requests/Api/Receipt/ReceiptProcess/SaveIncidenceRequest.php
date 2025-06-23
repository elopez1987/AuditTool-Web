<?php

namespace App\Http\Requests\Api\Receipt\ReceiptProcess;

use App\Rules\Base64;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SaveIncidenceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'shipmentid' => 'required|string|exists:receiptasn,shipmentid',
            'file' => ['required', new Base64()],
            'quantity' => 'required|numeric|min:1',
            'categoryincid' => 'required|exists:storagecategoryincidences,categoryincid',
            'categoryincdetailid' => 'required|exists:storagecategoryincidencedetails,categoryincdetailid',
            'unitmeasureid' => 'required|string|exists:inventoryunitsmeasure,unitmeasureid',
            'partnumberid' => 'required|string',
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
            'file.required' => 'La evidencia es un valor obligatorio.',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errors = $validator->errors();
        $response = response()->json([
            'ServiceName' => 'saveRegisterIncidenceReceipt',
            'ProcessDate' => now()->format('Y-m-d H:i:s'),
            'Code' => '-1',
            'Status' => 'Error',
            'Errors' => $errors,
            'Message' => $errors->first(),
            'Data' => [],
        ], 422);
        throw new HttpResponseException($response);
    }

}
