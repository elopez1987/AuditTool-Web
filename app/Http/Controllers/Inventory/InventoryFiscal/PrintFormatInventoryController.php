<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\Inventory\InventoryFiscal\InventoryCardCount;
use App\Models\Inventory\InventoryFiscal\InventoryLocationCount;

class PrintFormatInventoryController extends BaseController
{
    public function printFormat01($id)
    {
        $pathImage = SystemDetailParameter::where('parameterid', '=', 'PARAM-IMG')->first();
        $inventoryCardCount = InventoryCardCount::where('id', $id)->first();
        if (is_null($inventoryCardCount)) {
            return $this->errorResponse('No existe el número de cédula a imprimir.');
        }

        $locations = InventoryLocationCount::where('inventory_head_id', $inventoryCardCount->inventory_head_id)
            ->where('inventory_card_id', $inventoryCardCount->inventory_card_id)
            ->with(['inventorylocation:id,name,inventoryunitsmeasure_id', 'inventorypartnumber01:id,prtnum,lngdsc'])
            ->select(['id', 'inventory_location_id', 'inventory_head_id',
                'inventory_card_id', 'inventorypartnumber01_id', 'quantity01', 'user_count01_id'])
            ->get();

        $fortmat = 'Inventory.InventoryHead.CardDetail.Formats.Estandard.count01';
        $pdf = \PDF::setOptions([
            'isRemoteEnabled' => true,
            'chroot' => [
                realpath(base_path()),
                realpath($pathImage->value01),
            ],
            'httpContext' => stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ]),
        ])->loadView($fortmat, compact('inventoryCardCount', 'locations'));

        return $pdf->download('Cédula de conteo-' . $inventoryCardCount->id . '.pdf');

    }

    public function printFormat02($id)
    {
        $pathImage = SystemDetailParameter::where('parameterid', '=', 'PARAM-IMG')->first();
        $inventoryCardCount = InventoryCardCount::where('id', $id)->first();
        if (is_null($inventoryCardCount)) {
            return $this->errorResponse('No existe el número de cédula a imprimir.');
        }

        $locations = InventoryLocationCount::where('inventory_head_id', $inventoryCardCount->inventory_head_id)
            ->where('inventory_card_id', $inventoryCardCount->inventory_card_id)
            ->with(['inventorylocation:id,name,inventoryunitsmeasure_id', 'inventorypartnumber02:id,prtnum,lngdsc'])
            ->select(['id', 'inventory_location_id', 'inventory_head_id',
                'inventory_card_id', 'inventorypartnumber02_id', 'quantity02', 'user_count02_id'])
            ->get();

        $fortmat = 'Inventory.InventoryHead.CardDetail.Formats.Estandard.count02';
        $pdf = \PDF::setOptions([
            'isRemoteEnabled' => true,
            'chroot' => [
                realpath(base_path()),
                realpath($pathImage->value01),
            ],
            'httpContext' => stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ]),
        ])->loadView($fortmat, compact('inventoryCardCount', 'locations'));

        return $pdf->download('Cédula de conteo-' . $inventoryCardCount->id . '.pdf');
    }
}
