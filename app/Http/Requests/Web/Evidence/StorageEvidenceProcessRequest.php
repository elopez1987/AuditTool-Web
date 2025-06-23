<?php

namespace App\Http\Requests\Web\Evidence;

use Illuminate\Foundation\Http\FormRequest;

class StorageEvidenceProcessRequest extends FormRequest
{
    public function rules(): array
    {
        return [
//            'centerid' => 'required',
//            'warehouseid' => 'required',
            'shipmentid' => 'required|unique:storageevidenceoperations',
            'turnid' => 'required',
            'workday' => 'required',
//            'appointment' => 'required',
//            'econumber' => 'required',
            'carrierid' => 'required',
//            'boxnumber' => 'required',
            'locationid' => 'required',
            'razonstatusid' => 'required',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
