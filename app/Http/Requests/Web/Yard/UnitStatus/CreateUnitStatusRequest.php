<?php

namespace App\Http\Requests\Web\Yard\UnitStatus;

use Illuminate\Foundation\Http\FormRequest;

class CreateUnitStatusRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:50',
            'unitstatusid' => 'required|string|unique:storageunitstatus,unitstatusid|max:15'
        ];
    }

    public function authorize()
    {
        return true;
    }
}
