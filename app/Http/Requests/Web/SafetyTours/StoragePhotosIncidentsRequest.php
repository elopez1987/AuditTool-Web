<?php

namespace App\Http\Requests\Web\SafetyTours;

use Illuminate\Foundation\Http\FormRequest;

class StoragePhotosIncidentsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'storage_incidence_safety_id' => ['required', 'exists:storage_incidence_safeties'],
            'path' => ['required'],
            'create_by' => ['required', 'exists:users'],
            'created_at' => ['required', 'date'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
