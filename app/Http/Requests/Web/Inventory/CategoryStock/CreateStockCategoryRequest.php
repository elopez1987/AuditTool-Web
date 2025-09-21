<?php

namespace App\Http\Requests\Web\Inventory\CategoryStock;

use Illuminate\Foundation\Http\FormRequest;

class CreateStockCategoryRequest extends FormRequest
{
    public function rules()
    {
        return [
            'description' => 'required|string|max:30',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
