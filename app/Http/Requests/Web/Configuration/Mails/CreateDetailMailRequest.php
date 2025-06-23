<?php

namespace App\Http\Requests\Web\Configuration\Mails;

use Illuminate\Foundation\Http\FormRequest;

class CreateDetailMailRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'centerid' => 'required|string',
            'warehouseid' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|email'
        ];
    }
}
