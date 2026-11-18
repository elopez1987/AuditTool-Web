<?php

namespace App\Http\Requests\Web\Yard\Locations;

use Illuminate\Foundation\Http\FormRequest;

class CreateLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'zoneid' => 'required|exists:storagezones,id',
            'name' => 'required|string|max:50',
            'locationid' => ['required', 'string', 'max:20'],
        ];
    }
}
