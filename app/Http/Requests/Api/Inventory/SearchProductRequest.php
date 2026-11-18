<?php

namespace App\Http\Requests\Api\Inventory;

use App\Traits\Api\ApiValidationResponse;
use Illuminate\Foundation\Http\FormRequest;

class SearchProductRequest extends FormRequest
{
    use ApiValidationResponse;

    public function rules(): array
    {
        return [
            'product' => ['required', 'string', 'max:30'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'partnumberCode.required' => 'El número de parte del material es requerido ...',
            'partnumberCode.max' => 'El número de parte no puede ser mayor a 30 caracteres',
            'partnumberCode.string' => 'El número de parte debe ser un string',
        ];
    }
}
