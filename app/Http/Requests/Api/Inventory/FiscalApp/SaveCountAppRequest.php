<?php

namespace App\Http\Requests\Api\Inventory\FiscalApp;

use App\Traits\Api\General\ErrorResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class SaveCountAppRequest extends FormRequest
{
    use ErrorResponse;
    public function rules(): array
    {
        return [
            'locationid' => 'required',
            'partnumberid' => 'required',
            'quantity' => 'required:',
            'inventoryid' => 'required',
            'cardid' => 'required',
            'countid' => 'required',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator,'saveCountScanLocation');
    }
}
