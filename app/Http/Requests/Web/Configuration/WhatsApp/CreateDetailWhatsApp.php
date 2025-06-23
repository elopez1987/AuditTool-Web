<?php

namespace App\Http\Requests\Web\Configuration\WhatsApp;

use Illuminate\Foundation\Http\FormRequest;

class CreateDetailWhatsApp extends FormRequest
{
    public function rules()
    {
        return [
            'namecontact' => 'required|string|max:50',
            'phonenumber' => 'required|string|max:10',
            'phonenumber01' => 'string|max:10',
            'centerid' => 'required|exists:systemcenterdistributions,centerid',
            'warehouseid' => 'required|exists:systemwarehouses,warehouseid'
        ];
    }

    public function authorize()
    {
        return true;
    }
}
