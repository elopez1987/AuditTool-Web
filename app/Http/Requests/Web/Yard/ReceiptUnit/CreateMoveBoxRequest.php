<?php

namespace App\Http\Requests\Web\Yard\ReceiptUnit;

use Illuminate\Foundation\Http\FormRequest;

class CreateMoveBoxRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'folionumber' => 'required|numeric|max_digits:15|exists:receiptunits,id',
            'locationid' => 'required|exists:storagelocations,id',
            'zoneid' => 'required|exists:storagezones,id',
            //            'unitstatusid' => 'required|exists:storageunitstatus,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'folionumber.required' => 'The folio number is required.',
            'folionumber.exists' => 'The folio number does not exist.',
            'zoneid.required' => 'The zone is required.',
            'zoneid.exists' => 'The zone does not exist.',
            'locationid.required' => 'The location is required.',
            'locationid.exists' => 'The location does not exist.',
            //            'unitstatusid.required' => 'The unit status is required.',
            //            'unitstatusid.exists' => 'The unit status does not exist.',

        ];
    }
}
