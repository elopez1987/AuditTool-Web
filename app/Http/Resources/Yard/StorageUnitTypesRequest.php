<?php

namespace App\Http\Resources\Yard;

use Illuminate\Foundation\Http\FormRequest;

class StorageUnitTypesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'centerid' => ['required', 'exists:systemcenterdistributions,centerid'],
            'unittypeid' => ['required','max:10'],
            'description' => ['required','max:50'],
            'auxiliary01' => ['nullable','max:100'],
            'auxiliary02' => ['nullable','max:100'],
            'auxiliary03' => ['nullable','max:100'],
            'auxiliary04' => ['nullable','max:100'],
            'auxiliary05' => ['nullable','max:100'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
