<?php

namespace App\Http\Requests\Web\Receipt\ReceiptProcess;

use Illuminate\Foundation\Http\FormRequest;

class CreateReceiptPalletsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'partnumberid' => 'required',
            'quantity' => 'required|numeric|integer',
            'damage' => 'required|numeric|integer',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
