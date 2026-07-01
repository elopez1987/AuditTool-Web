<?php

namespace App\Http\Requests\SafetyTours\Incidents;

use Illuminate\Foundation\Http\FormRequest;

class SaveCategorySafetyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'centerid' => 'required|exists:systemcenterdistributions,centerid',
            'name' => 'required|string|max:255',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
