<?php

namespace App\Http\Requests\Web\Maintenance;

use Illuminate\Foundation\Http\FormRequest;

class StorageChekListTruckEvidencesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'storagechecklistslifttruck_id' => ['required', 'exists:storagechecklistslifttrucks'],
            'name' => ['required'],
            'value01' => ['nullable'],
            'value02' => ['nullable'],
            'value03' => ['nullable'],
            'schemaid' => ['required'],
            'statusid' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
