<?php

namespace App\Http\Requests\Web\Maintenance;

use Illuminate\Foundation\Http\FormRequest;

class StorageCheckListDetailLiftTruckRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'description' => 'required|string|max:100'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
