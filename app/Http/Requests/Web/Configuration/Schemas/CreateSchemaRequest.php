<?php

namespace App\Http\Requests\Web\Configuration\Schemas;

use Illuminate\Foundation\Http\FormRequest;

class CreateSchemaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'schemaid' => 'required|string|max:3',
            'name' => 'required|string|max:150',
            'auxiliary01' => 'nullable|string',
            'auxiliary02' => 'nullable|string',
            'auxiliary03' => 'nullable|string',
            'auxiliary04' => 'nullable|string',
            'auxiliary05' => 'nullable|string',
        ];
    }
}
