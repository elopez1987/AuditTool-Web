<?php

namespace App\Http\Requests\Web\Yard\Loto;

use Illuminate\Foundation\Http\FormRequest;

class CreateStorageLotoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'centerid' => 'required|exists:systemcenterdistributions,centerid',
            'description' => 'required|string|max:100',
            'quantity' => 'required|integer|min_digits:1|max_digits:3',
            'value01' => 'nullable|max_digits:100',
            'value02' => 'nullable|max_digits:100',
            'value03' => 'nullable|max_digits:100',
            'value04' => 'nullable|max_digits:100',
            'value05' => 'nullable|max_digits:100',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
