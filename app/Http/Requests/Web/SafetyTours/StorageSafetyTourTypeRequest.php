<?php

namespace App\Http\Requests\Web\SafetyTours;

use Illuminate\Foundation\Http\FormRequest;

class StorageSafetyTourTypeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'value01' => ['nullable'],
            'value02' => ['nullable'],
            'value03' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
