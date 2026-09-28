<?php

namespace App\Http\Requests\Web\Configuration\Parameters;

use Illuminate\Foundation\Http\FormRequest;

class SearchParameterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string'],
            'parameterid' => ['nullable', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
