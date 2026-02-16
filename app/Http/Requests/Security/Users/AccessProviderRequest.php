<?php

namespace App\Http\Requests\Security\Users;

use Illuminate\Foundation\Http\FormRequest;

class AccessProviderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'centerid' => ['required'],
            'providerid' => ['required', 'exists:receiptproviders'],
            'username' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
