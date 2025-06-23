<?php

namespace App\Http\Requests\Web\Shipments\Reports;

use Illuminate\Foundation\Http\FormRequest;

class ExportReportTravelRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'workdayin' => 'required',
            'workdayout' => 'required',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
