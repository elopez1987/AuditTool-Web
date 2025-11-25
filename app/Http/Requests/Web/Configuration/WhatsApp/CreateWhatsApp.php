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
            'auxiliary01' => 'nullable',
            'auxiliary02' => 'nullable',
            'auxiliary03' => 'nullable',
            'auxiliary04' => 'nullable',
            'auxiliary05' => 'nullable',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
