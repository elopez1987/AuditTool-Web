<?php

namespace App\Http\Requests\Web\Yard\Destination;

use Illuminate\Foundation\Http\FormRequest;

class CreateDestinationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'time' => 'required|numeric',
            'centerid' => 'required',
            'warehouseid' => 'required',
        ];
    }
}
