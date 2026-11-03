<?php

namespace App\Http\Requests\Api\Inventory;

use App\Traits\Api\ApiValidationResponse;
use Illuminate\Foundation\Http\FormRequest;

class CreateCategoryIncidenceRequest extends FormRequest
{
    use ApiValidationResponse;

    public function rules(): array
    {
        return [
            'centerid' => ['required', 'string', 'max:15', 'exists:systemcenterdistributions,centerid'],
            'description' => ['required', 'string', 'max:200'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
