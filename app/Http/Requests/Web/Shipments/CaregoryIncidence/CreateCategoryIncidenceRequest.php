<?php

namespace App\Http\Requests\Web\Shipments\CaregoryIncidence;

use Illuminate\Foundation\Http\FormRequest;

class CreateCategoryIncidenceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'centerid' => 'required|string|exists:systemcenterdistributions,centerid',
            'description' => 'required|string',
            'auxiliary01' => 'required|string|max:3',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
