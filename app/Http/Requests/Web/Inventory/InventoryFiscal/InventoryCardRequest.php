<?php

namespace App\Http\Requests\Web\Inventory\InventoryFiscal;

use Illuminate\Foundation\Http\FormRequest;

class InventoryCardRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            //            'centerid' => ['required', 'exists:systemcenterdistributions,centerid'],
            //            'warehouseid' => ['required', 'exists:systemwarehouses,warehouseid'],
            'name' => ['required'],
            'configurationflag' => ['nullable'],
            'auxiliary01' => ['nullable'],
            'auxiliary02' => ['nullable'],
            'auxiliary03' => ['nullable'],
            'inventory_area_id' => ['required', 'exists:inventory_areas,id'],
            'inventory_aisle_id' => ['required', 'exists:inventory_aisles,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
