<?php

namespace App\Http\Requests\Web\Configuration\Mails;

use Illuminate\Foundation\Http\FormRequest;

class StorageTaskMailRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'centerid' => ['required', 'exists:systemcenterdistributions'],
            'warehouseid' => ['required', 'exists:systemwarehouses'],
            'modeltype' => ['required'],
            'modelid' => ['required'],
            'mailid' => ['required'],
            'value01' => ['nullable'],
            'value02' => ['nullable'],
            'value03' => ['nullable'],
            'value04' => ['nullable'],
            'value05' => ['nullable'],
            'schemaid' => ['required', 'exists:systemschemas'],
            'statusid' => ['nullable', 'exists:systemstatus'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
