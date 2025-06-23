<?php

namespace App\Http\Requests\Web\Evidence;

use Illuminate\Foundation\Http\FormRequest;

class UploadImageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file' => 'required|mimes:jpg,jpeg,png,bmp',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
