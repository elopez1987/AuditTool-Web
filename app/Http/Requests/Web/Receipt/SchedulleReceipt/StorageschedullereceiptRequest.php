<?php

namespace App\Http\Requests\Web\Receipt\SchedulleReceipt;

use Illuminate\Foundation\Http\FormRequest;

class StorageschedullereceiptRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'centerid' => ['required', 'exists:systemcenterdistributions,centerid'],
            'warehouseid' => ['required', 'exists:systemwarehouses,warehouseid'],
            'workday' => ['required', 'date'],
            'turnid' => ['nullable', 'exists:storageturns,id'],
            'secuence' => ['nullable', 'exists:systemstatus,statusid'],
            'receiptid' => ['nullable', 'exists:receiptasn,id'],
            'value01' => ['nullable'],
            'value02' => ['nullable'],
            'value03' => ['nullable'],
            'value04' => ['nullable'],
            'value05' => ['nullable'],
            'schemaid' => ['required'],
            'statusid' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
