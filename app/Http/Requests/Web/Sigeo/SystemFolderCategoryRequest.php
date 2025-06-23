<?php

namespace App\Http\Requests\Web\Sigeo;

use Illuminate\Foundation\Http\FormRequest;

class SystemFolderCategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|max:100',
        ];
    }
}
