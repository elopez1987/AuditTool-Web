<?php

namespace App\Http\Requests\Web\Yard\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CreateCustomerRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'city' => 'required|string|max:190',
            'centerid' => 'required|string|exists:systemcenterdistributions,centerid',
            'warehouseid' => 'required|string|exists:systemwarehouses,warehouseid',
            'timetransfer' => 'required|numeric',
        ];
    }
}
