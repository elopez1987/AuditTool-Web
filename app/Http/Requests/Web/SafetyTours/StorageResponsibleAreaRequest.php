<?php

namespace App\Http\Requests\Web\SafetyTours;

use Illuminate\Foundation\Http\FormRequest;

class StorageResponsibleAreaRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'centerid' => ['required', 'exists:systemcenterdistributions,centerid'],
            'name' => ['required', 'max:100'],
            'auxiliary01' => ['nullable'],
            'auxiliary02' => ['nullable'],
            'auxiliary03' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
