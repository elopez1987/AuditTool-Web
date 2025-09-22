<?php

namespace App\Http\Requests\Web\Configuration\Parameters;

use Illuminate\Foundation\Http\FormRequest;

class CreateParameterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'parameterid' => 'required|string|unique:systemparameters,parameterid',
            'name' => 'required|string',
        ];
    }
}
