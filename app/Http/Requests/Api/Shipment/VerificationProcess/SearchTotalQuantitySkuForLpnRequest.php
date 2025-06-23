<?php

namespace App\Http\Requests\Api\Shipment\VerificationProcess;

use App\Traits\Api\General\ErrorResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class SearchTotalQuantitySkuForLpnRequest extends FormRequest
{
    use ErrorResponse;
    public function rules(): array
    {
        return [
            'shipmentid' => 'required|string|exists:orderdeliveries,shipmentid',
            'lpnid' => 'required|string|exists:shipmentcontainers,lpnid',
            'partnumberid' => 'required|int|exists:inventorypartnumbers,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        return $this->errorResponse($validator,'getTotalQuantityForSkuForLpns');
    }


}
