<?php

namespace App\Http\Requests\Web\Configuration\TableDataBase;

use Illuminate\Foundation\Http\FormRequest;

class CreateColumnTableRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'columnid' => 'required',
            'descriptionname' => 'required',
            'shortdesc' => 'required',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
