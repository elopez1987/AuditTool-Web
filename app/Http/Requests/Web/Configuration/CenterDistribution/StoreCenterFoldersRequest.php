<?php

namespace App\Http\Requests\Web\Configuration\CenterDistribution;

use Illuminate\Foundation\Http\FormRequest;

class StoreCenterFoldersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'folder' => 'nullable|array',
            'folder.*' => 'string|max:50',
        ];
    }
}
