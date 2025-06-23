<?php

namespace App\Http\Requests\Web\Yard\TruckCategory;

use Illuminate\Foundation\Http\FormRequest;

class CreateTruckCategory extends FormRequest
{
    public function rules()
    {
        return [
            'truckcategoryid' => 'required|unique:sqlsrv.shipmenttruckcategories,truckcategoryid',
            'description' => 'required|string|max:50'
        ];
    }

    public function authorize()
    {
        return true;
    }


}
