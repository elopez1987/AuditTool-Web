<?php

namespace App\Http\Requests\Api\Shipment\VerificationProcess;

use App\Traits\Api\General\ErrorResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UnpickRequest extends FormRequest
{
    use ErrorResponse;

    public function rules(): array
    {
        return [
            'shipmentid' => 'required|string|max:30|exists:orderdeliveries,shipmentid',
            'lpnid' => 'required|string|max:30|exists:shipmentstock,lpnid',
            'partnumberid' => 'required|integer|exists:inventorypartnumbers,id',
            'quantitystock' => 'required|integer|min:1',
            'quantity' => 'required|integer|min:1',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        return $this->errorResponse($validator, 'saveunpicklpn');
    }


}
