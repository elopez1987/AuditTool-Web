<?php

namespace App\Http\Requests\Web\Sigeo;

use Illuminate\Foundation\Http\FormRequest;

class SystemFolderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required'
        ];
    }
}
