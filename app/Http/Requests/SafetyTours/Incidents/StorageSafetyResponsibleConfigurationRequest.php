<?php

namespace App\Http\Requests\SafetyTours\Incidents;

use Illuminate\Foundation\Http\FormRequest;

class StorageSafetyResponsibleConfigurationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'storage_safety_responsible_area_id' => ['required', 'exists:storage_safety_responsible_areas'],
            'level01' => ['required', 'numeric'],
            'timeout01' => ['required', 'integer'],
            'attempts01' => ['required', 'integer'],
            'level02' => ['required', 'numeric'],
            'timeout02' => ['required', 'integer'],
            'attempts02' => ['required', 'integer'],
            'level03' => ['nullable', 'numeric'],
            'timeout03' => ['nullable', 'integer'],
            'attempts03' => ['nullable', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
