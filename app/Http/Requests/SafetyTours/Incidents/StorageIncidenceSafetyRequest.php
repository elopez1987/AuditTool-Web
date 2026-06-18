<?php

namespace App\Http\Requests\SafetyTours\Incidents;

use Illuminate\Foundation\Http\FormRequest;

class StorageIncidenceSafetyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'centerid' => ['required'],
            'stotage_category_safety_id' => ['required', 'exists:stotage_category_safeties'],
            'storage_safety_category_detail_id' => ['required', 'exists:storage_safety_category_details'],
            'name' => ['nullable'],
            'description' => ['required'],
            'created_by' => ['required', 'integer'],
            'closed_by' => ['nullable', 'integer'],
            'closed_at' => ['nullable', 'date'],
            'location' => ['nullable'],
            'auxiliary01' => ['nullable'],
            'auxiliary02' => ['nullable'],
            'auxiliary03' => ['nullable'],
            'schamaid' => ['required'],
            'statusid' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
