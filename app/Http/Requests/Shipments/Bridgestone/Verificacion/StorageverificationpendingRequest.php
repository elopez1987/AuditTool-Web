<?php

namespace App\Http\Requests\Shipments\Bridgestone\Verificacion;

use Illuminate\Foundation\Http\FormRequest;

class StorageverificationpendingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'centerid' => ['required', 'exists:systemcenterdistributions'],
            'warehouseid' => ['required', 'exists:systemwarehouses'],
            'orderid' => ['required', 'integer'],
            'countvigid' => ['required', 'integer'],
            'countoperid' => ['nullable', 'integer'],
            'countviguser' => ['required', 'integer'],
            'countoperuser' => ['nullable', 'integer'],
            'model_type' => ['nullable'],
            'schemaid' => ['required'],
            'statusid' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
