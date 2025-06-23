<?php

namespace App\Http\Requests\Web\Configuration\EdiToken;

use Illuminate\Foundation\Http\FormRequest;

class CreateEdiTokenRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'baseuri' => 'required|string|max:190',
            'client_id' => 'required|string|max:190',
            'client_secret' => 'required|string|max:190',
            'timeout' => 'required|numeric|min:1',
            'tokentype' => 'required|string|max:190',
            'servicetype' => 'required|string|max:190',

        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
