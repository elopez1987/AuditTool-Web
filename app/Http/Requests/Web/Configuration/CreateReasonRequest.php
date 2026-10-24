<?php

namespace App\Http\Requests\Web\Configuration;

use Illuminate\Foundation\Http\FormRequest;

class CreateReasonRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'razonstatusid' => ['required', 'string', 'max:10', 'unique:storagereasons,razonstatusid'],
            'type' => ['string', 'nullable', 'max:10'],
            'configurationflag' => ['nullable', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
