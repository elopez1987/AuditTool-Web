<?php

namespace App\Http\Requests\Web\Security\Users;

use Illuminate\Foundation\Http\FormRequest;

class AccessOriginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'origins' => ['required', 'array', 'min:1', 'exists:storageunittypes,id'],
            'username' => ['required', 'exists:users,username'],
            'origins.*' => ['integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
