<?php

namespace App\Http\Requests\Web\Yard\GenerateAccessWarehouse;

use Illuminate\Foundation\Http\FormRequest;

class CreateAccessWarehouseQrRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'centerid' => ['required','exists:systemcenterdistributions,centerid'],
            'warehouseid' => ['required','exists:systemwarehouses,warehouseid'],
            'operatorid' => ['required','exists:storageoperators,id'],
            'boxid' => ['required', 'exists:storagelabeltrucks,id'],
            'truckid' => ['nullable'],
            'shipmentid' => ['nullable'],
            'confirmationnumber' => ['nullable'],
            'datestart' => ['required', ],
            'datefinish' => ['required',],
            'auxiliary01' => ['nullable'],
            'auxiliary02' => ['nullable'],
            'auxiliary03' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
