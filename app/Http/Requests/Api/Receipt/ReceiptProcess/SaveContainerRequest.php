<?php

namespace App\Http\Requests\Api\Receipt\ReceiptProcess;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SaveContainerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'shipmentid' => 'required|string|exists:receiptasn,shipmentid',
            'partnumberid' => 'required|string|exists:inventorypartnumbers,prtnum',
            'quantity' => 'required|numeric|between:1,1000000',
            'lpnid' => 'required|numeric',
            'batch' => 'string',
            'productiondate' => 'date'
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
            'partnumberid.required' => 'El campo Sku es obligatorio.',
            'partnumberid.exists' => 'El campo Sku no existe.',
            'quantity.required' => 'El campo cantidad es obligatorio.',
            'lpnid.required' => 'El campo Lpn es obligatorio.',
            'productiondate.date' => 'El valor debe de ser tipo fecha.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        $response = response()->json([
            'ServiceName' => 'saveReceiptContainerInProceses',
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
