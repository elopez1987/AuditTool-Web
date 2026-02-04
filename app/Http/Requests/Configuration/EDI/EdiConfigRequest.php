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
            'centersender' => ['required'],
            'warehousesender' => ['required'],
            'centerrecipient' => ['required'],
            'warehouserecipient' => ['required'],
            'url' => ['required'],
            'user' => ['required'],
            'password' => ['required'],
            'version' => ['required'],
            'enviroment' => ['required'],
            'systemclient' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
