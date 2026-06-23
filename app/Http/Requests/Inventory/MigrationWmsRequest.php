<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class MigrationWmsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'locationd' => ['required'],
            'lpnizel' => ['required'],
            'lpnsap' => ['required'],
            'create_by' => ['required'],
            'photo' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
