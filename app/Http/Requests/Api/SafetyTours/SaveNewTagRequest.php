<?php

namespace App\Http\Requests\Api\SafetyTours;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SaveNewTagRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'tagid' => 'required|string|max:50|unique:storagetags,tagid',
            'name' => 'required|string|max:50',
            'latitude' => 'required|string|max:50',
            'longitude' => 'required|string|max:50',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }


    protected function failedValidation(Validator|\Illuminate\Contracts\Validation\Validator $validator)
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
