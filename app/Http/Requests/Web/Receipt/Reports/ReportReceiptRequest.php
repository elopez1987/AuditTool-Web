<?php

namespace App\Http\Requests\Web\Receipt\Reports;

use Illuminate\Foundation\Http\FormRequest;

class ReportReceiptRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'centerid' => 'required',
            'warehouseid' => 'required',
            'type' => 'required',
            'workdayin' => 'required',
            'workdayout' => 'required',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
