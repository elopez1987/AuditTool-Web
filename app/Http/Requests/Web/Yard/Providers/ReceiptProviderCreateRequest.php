<?php

namespace App\Http\Requests\Web\Yard\Providers;

use Illuminate\Foundation\Http\FormRequest;

class ReceiptProviderCreateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'centerid' => 'required|exists:systemcenterdistributions,centerid',
            'warehouseid' => 'required|exists:systemwarehouses,warehouseid',
            'name' => 'required|string|max:100',
            'providerid' => 'required|max:15',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
