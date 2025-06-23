<?php

namespace App\Http\Requests\Api\Yard\ReceiptUnit;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SaveSealToReceiptuUnitRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'barcode' => 'required|string|exists:receiptunits,barcode',
            'typeid' => 'required|string',
            'seal01' => 'required|string',
            'seal02' => 'string|nullable',
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
            'ServiceName' => 'savesealtoreceiptunit',
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
