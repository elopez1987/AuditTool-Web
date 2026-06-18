<?php

namespace App\Http\Requests\SafetyTours\Incidents;

use Illuminate\Foundation\Http\FormRequest;

class SaveEmailResponsibleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100',
            'phone' => 'required|string|max:100',
            'level' => 'required|integer|in:1,2,3',
            'attempts' => 'required|integer|in:1,2,3',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
