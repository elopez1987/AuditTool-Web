<?php

namespace App\Http\Requests\SafetyTours;

use Illuminate\Foundation\Http\FormRequest;

class StorageSafetyCategoryDetailRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'stotage_category_safety_id' => ['required', 'exists:stotage_category_safeties'],
            'name' => ['required'],
            'storage_safety_responsible_area_id' => ['required', 'exists:storage_safety_responsible_areas'],
            'configurationflag' => ['nullable'],
            'auxiliary01' => ['nullable'],
            'auxiliary02' => ['nullable'],
            'auxiliary03' => ['nullable'],
            'schamaid' => ['nullable'],
            'statusid' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
