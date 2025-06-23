<?php

namespace App\Http\Requests\Api\Yard\ReceiptUnit;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SaveSealToBoxRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'typeid' => 'required|string',
            'barcode' => 'required|string|exists:receiptunits,barcode',
            'sealid' => 'required|string|exists:storagesealloto,id',
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
            'ServiceName' => 'moveboxtolocation',
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
