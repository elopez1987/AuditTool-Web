<?php

namespace App\Http\Requests\Web\Yard\Carriers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCarrierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'centerid' => 'required|string|exists:systemcenterdistributions,centerid',
            'warehouseid' => 'required|string|exists:systemwarehouses,warehouseid',
            'name' => 'required|string|max:100',
            'carrierid' => [
                'required',
                'string',
                'max:50',
                Rule::unique('shipmentcarriers', 'carrierid')->where(function ($query) {
                    return $query->where('centerid', request()->input('centerid'));
                }),
            ],
            'auxiliary01' => 'nullable|string|max:100',
            'auxiliary02' => 'nullable|string|max:100',
            'auxiliary03' => 'nullable|string|max:100',
        ];
    }
}
