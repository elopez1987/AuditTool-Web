<?php

namespace App\Http\Requests\Web\Inventory\CategoryInventory;

use Illuminate\Foundation\Http\FormRequest;

class CreateCategoryInventory01 extends FormRequest
{
    public function rules()
    {
        return [
            'description' => 'required|string',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
