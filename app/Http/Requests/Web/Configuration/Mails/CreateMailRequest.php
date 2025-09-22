<?php

namespace App\Http\Requests\Web\Configuration\Mails;

use Illuminate\Foundation\Http\FormRequest;

class CreateMailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'mailid' => 'required|string|max:15|unique:systemmails,mailid',
            'name' => 'required|string|max:150',
        ];
    }
}
