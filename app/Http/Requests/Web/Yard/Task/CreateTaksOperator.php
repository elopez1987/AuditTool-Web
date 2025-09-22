<?php

namespace App\Http\Requests\Web\Yard\Task;

use Illuminate\Foundation\Http\FormRequest;

class CreateTaksOperator extends FormRequest
{
    public function rules()
    {
        return [
            'zoneid' => 'required|exists:storagezones,id',
            'locationid' => 'required|exists:storagelocations,id',
            'typeid' => 'required',
            'patieroid' => 'required_if:typeid,2',
        ];
    }

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'patieroid.required_if' => 'El nombre del patiero es obligatorio.',
            'typeid.required' => 'El tipo de tarea es obligatorio.',
            'zoneid.required' => 'La zona es obligatoria.',
            'locationid.required' => 'La ubicación es obligatoria.',
        ];
    }
}
