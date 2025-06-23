<?php

namespace App\Http\Requests\Web\Shipments\OrderDelivery;

use Illuminate\Foundation\Http\FormRequest;

class CreateDeliveryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'billingdocument' => 'required|numeric|max_digits:25',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
