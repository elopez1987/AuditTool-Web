<?php

namespace App\Http\Requests\Web\Shipments\IncidenceJourney;

use Illuminate\Foundation\Http\FormRequest;

class CreateIncidenceShipmentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "partnumberid" => 'required',
            "quantity" => 'required|numeric',
            "responsible" => 'required|string',
            "categoryincid" => 'required',
            "categoryincdetailid" => 'required',
            "responsiblecorr" => 'required|string',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
