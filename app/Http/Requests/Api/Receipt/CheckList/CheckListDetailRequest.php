<?php

namespace App\Http\Requests\Api\Receipt\CheckList;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CheckListDetailRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => 'required|string|exists:storagechecklistdetails,auxiliary01'
        ];
    }
    public function authorize(): bool
    {
        return true;
    }
    public function messages()
    {
        return [
            'type.required' => 'El valor Tipo es obligatorio.',
            'type.string' => 'El valor Tipo debe de ser una cadena.',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        $response = response()->json([
            'ServiceName' => 'getDetailCheckListForReceipt',
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
