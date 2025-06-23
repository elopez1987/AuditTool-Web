<?php

namespace App\Http\Requests\Web\Maintenance;

use Illuminate\Foundation\Http\FormRequest;

class CreateStorageLiftTruckRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'serialnumber' => 'required|string|max:50|unique:storagelifttrucks',
            'numbertruck' => 'required|string|max:20',
            'typeid' => 'required',
            'brandid' => 'required',
            'modelid' => 'required',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
