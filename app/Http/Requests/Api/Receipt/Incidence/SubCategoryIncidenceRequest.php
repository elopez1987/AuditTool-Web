<?php

namespace App\Http\Requests\Api\Receipt\Incidence;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SubCategoryIncidenceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'categoryincid' => 'required|exists:storagecategoryincidences,categoryincid',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'categoryincid.required' => 'La categoria es un valor obligatorio.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        $response = response()->json([
            'ServiceName' => 'getReceiptSubCategoryIncidence',
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
