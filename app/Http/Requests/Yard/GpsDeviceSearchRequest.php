<?php

namespace App\Http\Requests\Yard;

use Illuminate\Foundation\Http\FormRequest;

class GpsDeviceSearchRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'imei' => ['string', 'nullable'],
            'sim_number' => ['string', 'nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
