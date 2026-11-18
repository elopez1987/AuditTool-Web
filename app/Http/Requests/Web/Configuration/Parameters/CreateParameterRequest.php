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
            'auxiliary01' => ['nullable', 'string'],
            'auxiliary02' => ['nullable', 'string'],
            'auxiliary03' => ['nullable', 'string'],
            'auxiliary04' => ['nullable', 'string'],
            'auxiliary05' => ['nullable', 'string'],
            'auxiliary06' => ['nullable', 'string'],
            'auxiliary07' => ['nullable', 'string'],
            'auxiliary08' => ['nullable', 'string'],
            'auxiliary09' => ['nullable', 'string'],
            'auxiliary10' => ['nullable', 'string'],
        ];
    }
}
