<?php

namespace App\Http\Requests\Web\Yard\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class CreateMovUnitForInventoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'zoneid' => 'required',
            'locationid' => 'required',
            'unitstatusid' => 'required',
            'folionumber' => 'required',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
