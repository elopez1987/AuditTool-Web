<?php

namespace App\Http\Requests\Web\Shipments\SchedulleInverse;

use Illuminate\Foundation\Http\FormRequest;

class CreateScheduleInverseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'shipmentid' => 'required'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
