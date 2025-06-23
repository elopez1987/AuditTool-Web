<?php

namespace App\Http\Requests\Web\SafetyTours;

use Illuminate\Foundation\Http\FormRequest;

class CreateTagWebRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'tagid' => 'required|string|max:100',
            'name' => 'required|string|max:100',
            'latitude' => 'required|string|max:100',
            'longitude' => 'required|string|max:100',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
