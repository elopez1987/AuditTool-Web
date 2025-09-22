<?php

namespace App\Http\Requests\Web\Maintenance;

use Illuminate\Foundation\Http\FormRequest;

class CreateCheckListLiftTrucksRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'typeid' => 'required|string|max:5',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
