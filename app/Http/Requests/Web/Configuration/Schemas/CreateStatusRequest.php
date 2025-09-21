<?php

namespace App\Http\Requests\Web\Configuration\Schemas;

use Illuminate\Foundation\Http\FormRequest;

class CreateStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'statusid' => 'required|string|max:10|unique:systemstatus,statusid',
            'namestatus' => 'required|string|max:100',
//            'comment' => 'required|string|max:50',
        ];
    }
}
