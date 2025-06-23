<?php

namespace App\Http\Requests\Api\SafetyTours;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorageSafetyTourDetailRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'tagid' => 'required|string|max:50|exists:storagetags,tagid',
            'tourid' => 'required|numeric|exists:storagesafetytours,id',
            'latitude' => 'required|string|max:50',
            'longitude' => 'required|string|max:50',
            'comment' => 'nullable|string|max:200',
            'statustype' => 'required|string|max:50|exists:systemstatus,statusid'
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
            'ServiceName' => 'storagetags.store',
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
