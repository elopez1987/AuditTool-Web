<?php

namespace App\Http\Requests\Web\Yard\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class CreateNewInventoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'centerid' => ['required', 'exists:systemcenterdistributions,centerid'],
            'statustypeid' => ['required', 'exists:systemstatus,statusid'],
            'description' => ['required', 'string', 'max:100'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
