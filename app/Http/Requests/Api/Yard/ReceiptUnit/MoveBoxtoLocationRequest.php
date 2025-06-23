<?php

namespace App\Http\Requests\Api\Yard\ReceiptUnit;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class MoveBoxtoLocationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'barcode' => 'required|string|exists:receiptunits,barcode',
            'barcodelocation' => 'required|string|exists:storagelocations,barcode'
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
