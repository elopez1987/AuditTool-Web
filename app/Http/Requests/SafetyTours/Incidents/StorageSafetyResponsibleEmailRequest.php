<?php

namespace App\Http\Requests\SafetyTours\Incidents;

use Illuminate\Foundation\Http\FormRequest;

class StorageSafetyResponsibleEmailRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'storage_safety_responsible_area_id' => ['required', 'exists:storage_safety_responsible_areas'],
            'name' => ['required'],
            'email' => ['required', 'email', 'max:254'],
            'phone' => ['nullable'],
            'level' => ['required', 'integer'],
            'attempts' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
