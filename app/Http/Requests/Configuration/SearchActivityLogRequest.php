<?php

namespace App\Http\Requests\Configuration;

use Illuminate\Foundation\Http\FormRequest;

class SearchActivityLogRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'description' => ['nullable', 'string'],
            'model' => ['nullable', 'string'],
            'subject_id' => ['nullable', 'int'],
            'userid' => ['nullable', 'int'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
