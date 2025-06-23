<?php

namespace App\Http\Requests\Shipments\ScheduleInverse;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleInverseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'centerid' => 'required|string|exists:systemcenterdistributions,centerid',
            'warehouseid' => 'required|string|exists:systemwarehouses,warehouseid',
            'name' => 'required|string|max:100',
            'startdate' => 'required|date',
            'finishdate' => 'required|date',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
