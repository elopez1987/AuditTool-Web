<?php

namespace App\Http\Resources;

use App\Models\Inventory\PartNumber\InventoryPartnumber;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin InventoryPartnumber
 */
class InventoryPartNumbersResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            /**
             * Identificador interno del producto; no es el código usado en la búsqueda GET.
             *
             * @example 1
             */
            'partnumberId' => $this->id,
            /**
             * Código del número de parte utilizado en el parámetro de la búsqueda GET.
             *
             * @example SKU-001
             */
            'partnumberCode' => $this->prtnum,
            /**
             * Descripción del producto.
             */
            'description' => $this->lngdsc,
        ];
    }
}
