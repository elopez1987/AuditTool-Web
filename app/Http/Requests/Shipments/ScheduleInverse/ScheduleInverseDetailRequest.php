<?php

namespace App\Http\Requests\Shipments\ScheduleInverse;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleInverseDetailRequest extends FormRequest
{
    public function rules(): array
    {
        return [

        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
