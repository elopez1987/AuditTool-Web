<?php

namespace App\Http\Requests\Web\Inventory\CategoryStock;

use Illuminate\Foundation\Http\FormRequest;

class CreateStockCategoryRequest extends FormRequest
{
    public function rules()
    {
        return [
            'centerid' => 'required|exists:systemcenterdistributions,centerid',
            'warehouseid' => 'required|exists:systemwarehouses,warehouseid',
            'description' => 'required|string|max:30',
            'stockcategoryid' => 'nullable|string|max:30',
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
