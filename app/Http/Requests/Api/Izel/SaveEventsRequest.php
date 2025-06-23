<?php

namespace App\Http\Requests\Api\Izel;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Validator;

class SaveEventsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'centerid' => 'required|string|exists:systemcenterdistributions,code',
            'folionumberid' => 'required|integer|exists:receiptunits,id',
            'typeid' => 'required|integer|exists:storagereasons,id',
            'createdate' => 'required|date_format:Y-m-d H:i:s',
            'shipmentid' => 'max:30'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages()
    {
        return [
            'folionumberid.required' => 'Folio Number ID is required',
            'folionumberid.exists' => 'Folio Number ID does not exist',
            'folionumberid.integer' => 'Folio Number ID must be an integer',
            'typeid.required' => 'Type ID is required',
            'typeid.exists' => 'Type ID does not exist',
            'typeid.integer' => 'Type ID must be an integer',
            'createdate.required' => 'Created Date is required',
            'createdate.date_format' => 'Created Date must be a valid date',
            'shipmentid.max' => 'Shipment ID must be less than 30 characters'
        ];
    }

    protected function failedValidation(Validator|\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errors = $validator->errors();
        $response = response()->json([
            'ServiceName' => 'saveeventsunits',
            'ProcessDate' => Carbon::now()->format('Y-m-d H:m:s'),
            'Code' => '-1',
            'Status' => 'Error',
            'Errors' => $errors,
            'Message' => $errors->first(),
            'Data' => [],
        ], 422);
        throw new HttpResponseException($response);
    }


}
