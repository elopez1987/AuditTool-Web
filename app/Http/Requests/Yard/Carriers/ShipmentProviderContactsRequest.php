<?php

namespace App\Http\Requests\Yard\Carriers;

use Illuminate\Foundation\Http\FormRequest;

class ShipmentProviderContactsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'centerid' => ['required', 'exists:systemcenterdistributions,centerid'],
            'warehouseid' => ['required', 'exists:systemwarehouses,warehouseid'],
            'providerid' => ['required', 'exists:receiptproviders,id'],
            'name' => ['required', 'string', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => ['required', 'email', 'max:254'],
            'phone' => ['required', 'string', 'max:10', 'regex:/^[0-9]+$/'],
            'value01' => ['nullable', 'string', 'regex:/^[a-zA-Z0-9\s]+$/'],
            'value02' => ['required', 'string', 'regex:/^[a-zA-Z0-9\s]+$'],
            'value03' => ['required', 'string', 'regex:/^[a-zA-Z0-9\s]+$/'],
            'created_by' => ['required', 'exists:users,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
