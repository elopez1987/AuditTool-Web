<?php

namespace App\Http\Requests\Web\Yard\Reports;

use Illuminate\Foundation\Http\FormRequest;

class FilterReportYardRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'centerid' => 'required|string',
            'type' => 'required|string',
            'workdayin' => 'required',
            'workdayout' => 'required'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
