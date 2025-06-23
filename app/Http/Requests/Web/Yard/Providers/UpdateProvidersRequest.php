<?php

namespace App\Http\Requests\Web\Yard\Providers;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProvidersRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:150',
            'providerid' => 'required|string|max:15',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
