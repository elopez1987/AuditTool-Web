<?php

namespace App\Http\Requests\Configuration;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStatusRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'centerid' => 'nullable|string|max:30|exists:systemcenterdistributions,centerid',
            'warehouseid' => 'nullable|string|max:30|exists:systemwarehouses,warehouseid',
            'schemaid' => 'required|string|max:3|exists:systemschemas,schemaid',
            'statusid' => 'required|string|max:10|unique:systemstatus,statusid',
            'name' => 'required|string|max:100',
            'comment' => 'nullable|string',
            'auxiliary01' => 'nullable|string',
            'auxiliary02' => 'nullable|string',
            'auxiliary03' => 'nullable|string',
            'auxiliary04' => 'nullable|string',
            'auxiliary05' => 'nullable|string',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
