<?php

namespace App\Http\Requests\Web\Yard\Geofence;

use Illuminate\Foundation\Http\FormRequest;

class CreateGeofencePointRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'geofence_id' => ['required', 'exists:geofences'],
            'sequence' => ['required', 'integer'],
            'latitude' => ['required', 'decimal:2'],
            'longitude' => ['required', 'decimal:2'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
