<?php

namespace App\Http\Requests\Web\Sigeo;

use Illuminate\Foundation\Http\FormRequest;

class SystemDocumentsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required',
            'folderid' => 'required',
            'categoryid' => 'required',
            'code' => 'required',
            'level' => 'required|numeric',
            'aplicationdate' => 'required',
            'updateddate' => 'required',
        ];
    }
}
