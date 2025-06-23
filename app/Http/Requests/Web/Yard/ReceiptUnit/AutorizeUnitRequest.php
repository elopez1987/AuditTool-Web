<?php

namespace App\Http\Requests\Web\Yard\ReceiptUnit;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AutorizeUnitRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'folionumber' => 'required|numeric|max_digits:20|exists:receiptunits,id',
            'centerid' => 'required|string|max:20|exists:systemcenterdistributions,centerid',
            'warehouseid' => 'required|string|max:20|exists:systemwarehouses,warehouseid',
            'razonstatusid' => 'required|exists:storagereasons,id',
//            'ticket' => 'nullable|max_digits:20',
//            'weightout' => 'nullable|max_digits:15',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator|\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errors = $validator->errors();
        $response = response()->json([
            'ServiceName' => 'autoizetransportstore',
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
