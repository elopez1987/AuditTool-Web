<?php

namespace App\Http\Requests\Api\Yard\ReceiptUnit;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SaveEvidenceForCheckListQIRequest extends FormRequest
{
    public function rules(): array
    {
        return [
           'folionumber' => 'required|string|exists:receiptunits,barcode',
            'file' => 'required',
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
            'ServiceName' => 'saveEvidenceForCheckListQI',
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
