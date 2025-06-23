<?php

namespace App\Http\Requests\Web\Shipments\VerifyProcess;

use Illuminate\Foundation\Http\FormRequest;

class MoveBoxPickRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'shipmentid' => 'required|integer|exists:orderdeliveries,id',
            'lpnid' => 'required|numeric',
            'partnumberid' => 'required|integer|exists:inventorypartnumbers,id',
            'quantitystock' => 'required|integer|min:1',
            'quantity' => 'required|integer|min:1',
            'lpnidrec' => 'required|numeric',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
