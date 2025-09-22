<?php

namespace App\Http\Requests\Web\Configuration\TableDataBase;

use Illuminate\Foundation\Http\FormRequest;

class CreateSystemTableRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'tableid' => 'required|string',
            'description' => 'required|string',
            'notes' => 'required|string',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
