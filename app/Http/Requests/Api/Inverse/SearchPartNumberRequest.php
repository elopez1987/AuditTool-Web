<?php

namespace App\Http\Requests\Api\Inverse;

use App\Rules\PartnumberOrBarcodeExists;
use App\Traits\Api\General\ErrorResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class SearchPartNumberRequest extends FormRequest
{
    use ErrorResponse;
    public function rules(): array
    {
        return [
            'partnumberid' => ['required',new PartnumberOrBarcodeExists()],
        ];
    }
    public function authorize(): bool
    {
        return true;
    }
    public function messages(): array
    {
        return [
            'partnumberid.required' => 'El número de parte es obligatorio.',
            'partnumberid.exists' => 'El número de parte no existe.',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator, 'getSkuForInverse');
    }

}
