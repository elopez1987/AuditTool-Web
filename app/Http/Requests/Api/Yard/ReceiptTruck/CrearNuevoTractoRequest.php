<?php

namespace App\Http\Requests\Api\Yard\ReceiptTruck;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CrearNuevoTractoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'carrierid' => 'required|exists:shipmentcarriers,id',
            'trucknumber' => 'required',
            'operatorname' => 'required|string|max:255',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }


    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        $response = response()->json([
            'ServiceName' => 'saveNewTruckInCenter',
            'ProcessDate' => Carbon::now()->format('Y-m-d H:m:s'),
            'Code' => '-1',
            'Status'=>'Error',
            'Errors' => $errors,
            'Message' => $errors->first(),
            'Data' => [],
        ],422);
        throw new HttpResponseException($response);
    }



}
