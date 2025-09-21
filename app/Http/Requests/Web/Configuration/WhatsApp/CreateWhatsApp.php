<?php

namespace App\Http\Requests\Web\Configuration\WhatsApp;

use Illuminate\Foundation\Http\FormRequest;

class CreateWhatsApp extends FormRequest
{
    public function rules()
    {
        return [
            'whatsappid' => 'required|string|max:15|unique:systemwhatsapps,whatsappid',
            'description' => 'required|string|max:100',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
