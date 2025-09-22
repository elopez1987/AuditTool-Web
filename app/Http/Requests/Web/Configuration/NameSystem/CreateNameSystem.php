<?php

namespace App\Http\Requests\Web\Configuration\NameSystem;

use Illuminate\Foundation\Http\FormRequest;

class CreateNameSystem extends FormRequest
{
    public function authorize(): bool
    {
        return false;
    }

    public function rules(): array
    {
        return [

        ];
    }
}
