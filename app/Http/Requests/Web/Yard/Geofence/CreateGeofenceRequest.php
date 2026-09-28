<?php

namespace App\Http\Requests\Web\Yard\Geofence;

use Illuminate\Foundation\Http\FormRequest;

class CreateGeofenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * El mapa envía los puntos del polígono como JSON en un campo oculto;
     * se decodifica a array antes de correr las reglas de validación.
     */
    protected function prepareForValidation(): void
    {
        if (is_string($this->input('points'))) {
            $this->merge([
                'points' => json_decode((string) $this->input('points'), true) ?? [],
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'centerid' => ['required', 'string', 'exists:systemcenterdistributions,centerid'],
            'name' => ['required', 'string', 'max:100'],
            'type' => ['nullable', 'string'],
            'points' => ['required', 'array', 'min:3'],
            'points.*.lat' => ['required', 'numeric', 'between:-90,90'],
            'points.*.lng' => ['required', 'numeric', 'between:-180,180'],
        ];
    }
}
