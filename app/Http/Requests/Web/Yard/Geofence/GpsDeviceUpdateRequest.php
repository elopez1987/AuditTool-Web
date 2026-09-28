<?php

namespace App\Http\Requests\Web\Yard\Geofence;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GpsDeviceUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'imei' => [
                'required',
                'max:30',
                Rule::unique('gps_devices', 'imei')->ignore($this->gps_device->id),
            ],
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
