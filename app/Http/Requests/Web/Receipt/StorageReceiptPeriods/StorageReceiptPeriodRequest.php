<?php

namespace App\Http\Requests\Web\Receipt\StorageReceiptPeriods;

use Illuminate\Foundation\Http\FormRequest;

class StorageReceiptPeriodRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'centerid' => ['required', 'exists:systemcenterdistributions,centerid'],
            'warehouseid' => ['required', 'exists:systemwarehouses,warehouseid'],
            'name' => ['required', 'string', 'max:50'],
            'startdate' => ['required', 'date'],
            'finishdate' => ['required', 'date'],
            'auxiliary01' => ['nullable'],
            'auxiliary02' => ['nullable'],
            'auxiliary03' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

}
