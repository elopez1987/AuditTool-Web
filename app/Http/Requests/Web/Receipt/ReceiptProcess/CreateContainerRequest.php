<?php

namespace App\Http\Requests\Web\Receipt\ReceiptProcess;

use Illuminate\Foundation\Http\FormRequest;

class CreateContainerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'quantity' => 'required',
            'partnumberid' => 'required',
            'lpnid' => 'required'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
