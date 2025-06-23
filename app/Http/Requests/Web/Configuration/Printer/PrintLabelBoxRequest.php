<?php

namespace App\Http\Requests\Web\Configuration\Printer;

use Illuminate\Foundation\Http\FormRequest;

class PrintLabelBoxRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'centerid' => 'required',
            'quantity' => 'required',
            'printerid' => 'required',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
