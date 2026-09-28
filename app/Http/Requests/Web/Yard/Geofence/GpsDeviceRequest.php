<?php

namespace App\Http\Requests\Web\Yard\Geofence;

use Illuminate\Foundation\Http\FormRequest;

class GpsDeviceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'centerid' => ['required', 'string', 'exists:systemcenterdistributions,centerid'],
            'imei' => ['required', 'unique:gps_devices,imei', 'max:30'],
            'model' => ['required'],
            'sim_number' => ['nullable', 'max:10'],
            'value01' => ['nullable'],
            'value02' => ['nullable'],
            'value03' => ['nullable'],
            'value04' => ['nullable'],
            'value05' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
