<?php

namespace App\Http\Requests\Web\Yard\CheckList;

use Illuminate\Foundation\Http\FormRequest;

class CreateCheckListRequest extends FormRequest
{
    public function rules()
    {
        return [
            'centerid' => 'required|string|exists:systemcenterdistributions,centerid',
            'warehouseid' => 'required|string|exists:systemwarehouses,warehouseid',
            'name' => 'required|string|max:50',
            'type' => 'required|string|max:5',
            'auxiliary01' => 'nullable|string|max:100',
            'auxiliary02' => 'nullable|string|max:100',
            'auxiliary03' => 'nullable|string|max:100',
            'auxiliary04' => 'nullable|string|max:100',
            'auxiliary05' => 'nullable|string|max:100',
        ];
    }

    public function authorize()
    {
        return true;
    }

}
