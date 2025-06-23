<?php

namespace App\Http\Requests\Web\SafetyTours;

use Illuminate\Foundation\Http\FormRequest;

class StorageSafetyToursRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'datetours' => 'required|date',
            'secuence' => 'required|numeric',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
