<?php

namespace App\Http\Requests\Web\Yard\ReceiptUnit;

use Illuminate\Foundation\Http\FormRequest;

class AutorizeExitRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'folionumber' => 'required|numeric|max_digits:20|exists:receiptunits,id',
            'zoneid' => 'nullable|exists:storagezones,id',
            'locationid' => 'nullable|exists:storagelocations,id',
            'unitstatusid' => 'nullable|exists:storageunitstatus,id',
            'comment' => 'nullable|string|max:255',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
