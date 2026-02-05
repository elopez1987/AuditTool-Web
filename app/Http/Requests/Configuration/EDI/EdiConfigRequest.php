<?php

namespace App\Http\Requests\Configuration\EDI;

use Illuminate\Foundation\Http\FormRequest;

class EdiConfigRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'edi' => ['required'],
            'type' => ['required'],
            'standard' => ['required'],
            'description' => ['required'],
            'sendername' => ['required'],
            'centersender' => ['nullable'],
            'warehousesender' => ['nullable'],
            'centerrecipient' => ['nullable'],
            'warehouserecipient' => ['nullable'],
            'url' => ['nullable'],
            'userid' => ['nullable'],
            'password' => ['nullable'],
            'version' => ['nullable'],
            'enviroment' => ['required'],
            'systemclient' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
