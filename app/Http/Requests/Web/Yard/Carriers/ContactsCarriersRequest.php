<?php

namespace App\Http\Requests\Web\Yard\Carriers;

use App\Models\Yard\Carriers\ShipmentCarrier;
use Illuminate\Foundation\Http\FormRequest;

class ContactsCarriersRequest extends FormRequest
{
    protected ?ShipmentCarrier $carrier = null;

    // Antes de validar, agregamos centerid, warehouseid, carrierid
    protected function prepareForValidation()
    {
        // Recupera el ID desde la ruta
        $carrierId = $this->route('id');

        // Cargar carrier con control de acceso
        $carrier = ShipmentCarrier::controlAccessUser()->findOrFail($carrierId);

        $this->carrier = $carrier;

        // Merge de valores al request antes de validar
        $this->merge([
            'centerid' => $carrier->centerid,
            'warehouseid' => $carrier->warehouseid,
            'carrierid' => $carrier->id,
        ]);
    }

    public function rules(): array
    {
        return [
            'centerid' => 'required|exists:systemcenterdistributions,centerid',
            'warehouseid' => 'required|exists:systemwarehouses,warehouseid',
            'carrierid' => 'required|integer|exists:shipmentcarriers,id',

            'name' => 'required|string|max:70',
            'phone' => 'required|numeric|max_digits:10',
            'email' => 'nullable|email',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
