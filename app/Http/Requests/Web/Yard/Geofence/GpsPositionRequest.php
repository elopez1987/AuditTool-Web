<?php

namespace App\Http\Requests\Web\Yard\Geofence;

use Illuminate\Foundation\Http\FormRequest;

class GpsPositionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'gps_device_id' => ['required', 'exists:gps_devices'],
            'latitude' => ['required', 'decimal:2'],
            'longitude' => ['required', 'decimal:2'],
            'speed' => ['required', 'decimal:2'],
            'heading' => ['required', 'decimal:2'],
            'altitude' => ['required', 'decimal:2'],
            'satellites' => ['required', 'integer'],
            'battery_level' => ['required', 'integer'],
            'recorded_at' => ['required', 'date'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
