<?php

namespace App\Http\Requests\Api\Shipment\VerificationProcess;

use App\Traits\Api\General\ErrorResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class SaveMoveLpnToLpnRequest extends FormRequest
{
    use ErrorResponse;
    public function rules(): array
    {
        return [
            'shipmentid' => 'required|string|exists:orderdeliveries,shipmentid',
            'lpnid' => 'required|string|exists:shipmentcontainers,lpnid',
            'partnumberid' => 'required|int|exists:inventorypartnumbers,id',
            'quantity' => 'required|integer|min:1',
            'quantitystock' => 'required|integer|min:1',
            'lpnidrec' => 'required|string',

        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function (Validator $validator) {
            $data = $this->all();

            if ($data['quantitystock'] < $data['quantity']) {
                $validator->errors()->add('quantity', 'La cantidad a mover no puede ser mayor a la existencia.');
            }

            if ($data['lpnid'] == $data['lpnidrec']) {
                $validator->errors()->add('lpnid', 'El Lpn original y el Lpn que recibe no puede ser el mismo valor.');
            }
        });
    }



    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        return $this->errorResponse($validator,'saveMoveBox');
    }


}
