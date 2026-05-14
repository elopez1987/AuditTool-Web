<?php

namespace App\Http\Requests\Web\Inventory\InventoryPartNumber;

use Illuminate\Foundation\Http\FormRequest;

class CreatePartNumberRequest extends FormRequest
{
    public function rules()
    {
        return [
            'centerid' => 'required|exists:systemcenterdistributions,centerid',
            'warehouseid' => 'required|exists:systemwarehouses,warehouseid',
            'category01id' => 'required|exists:inventorypartnumbercategory01,id',
            'category02id' => 'required|exists:inventorypartnumbercategory02,id',
            'prtnum' => 'required|string|unique:inventorypartnumbers,prtnum|max:25',
            'lngdsc' => 'required|string',
            'untpal' => 'required|numeric|max_digits:5',
            'untcas' => 'required|numeric|max_digits:5',
            'barcode' => 'required|string|max:30',
            'stkuom' => 'required',
            'shelflife' => 'required|numeric|max_digits:5',
            'codedun' => 'nullable|string|max:30',
            'codeean' => 'nullable|string|max:30',
            'auxiliary01' => 'nullable|string|max:190',
            'auxiliary02' => 'nullable|string|max:190',
            'auxiliary03' => 'nullable|string|max:190',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
