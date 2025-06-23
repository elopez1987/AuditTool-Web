<?php

namespace App\Http\Requests\SafetyTours;

use Illuminate\Foundation\Http\FormRequest;

class StorageSafetyTourTypeDetailRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'typeid' => ['required', 'exists:storagesafetytourtypes'],
            'tagid' => ['required', 'exists:storagetags'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
