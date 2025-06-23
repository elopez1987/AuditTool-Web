<?php

namespace App\Http\Requests\Web\Inventory\InventoryPartNumber;

use Illuminate\Foundation\Http\FormRequest;

class CreatePartNumberRequest extends FormRequest
{
    public function rules()
    {
        return [
            'category01id' => 'required',
            'category02id' => 'required',
            'prtnum' => 'required|string',
            'lngdsc' => 'required|string',
            'untpal' => 'required|numeric',
            'untcas' => 'required|numeric',
            'barcode' => 'required',
            'stkuom' => 'required',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
