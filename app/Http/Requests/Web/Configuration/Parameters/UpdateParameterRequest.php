<?php

namespace App\Http\Requests\Web\Configuration\Parameters;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateParameterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'parameterid' => ['required', 'string',
                Rule::unique('systemparameters', 'parameterid')
                    ->ignore($this->systemparameter->parameterid, 'parameterid'), ],
            'name' => 'required|string',
            'auxiliary01' => ['nullable', 'string'],
            'auxiliary02' => ['nullable', 'string'],
            'auxiliary03' => ['nullable', 'string'],
            'auxiliary04' => ['nullable', 'string'],
            'auxiliary05' => ['nullable', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
