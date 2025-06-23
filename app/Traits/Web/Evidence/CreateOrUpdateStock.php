<?php

namespace App\Traits\Web\Evidence;

use App\Models\Evidence\StorageEvidenceStock;
use App\Models\Inventory\Categories\InventoryPartNumberCategory01;
use App\Models\Inventory\Categories\InventoryPartNumberCategory02;
use App\Models\Inventory\PartNumber\InventoryPartnumber;

trait CreateOrUpdateStock
{

    public function searchPartNumber($partnumber, $shipment)
    {
        $partnumberid = InventoryPartnumber::controlAccessUser()
            ->where('prtnum', '=', trim($partnumber))
            ->select(['id', 'prtnum', 'lngdsc'])->first();

        if (empty($partnumberid)) {
            $partnumberid = $this->createPartNumber($partnumber, $shipment);
        }

        return $partnumberid;
    }

    public function createPartNumber($partnumber, $shipment)
    {
        $cat01 = InventoryPartNumberCategory01::controlAccessUser()->first();
        $cat02 = InventoryPartNumberCategory02::controlAccessUser()->first();

        $partnumberNew = InventoryPartnumber::updateOrCreate(
            [
                'centerid' => $shipment->centerid,
                'warehouseid' => $shipment->warehouseid,
                'prtnum' => $partnumber,
            ],
            [
                'lngdsc' => 'Sin Descripcion (Revisar)',
                'abccod' => 'A',
                'shelflife' => '360',
                'untcas' => '1',
                'untpal' => '1',
                'category01id' => $cat01->id,
                'category02id' => $cat02->id,
                'barcode' => $partnumber,
                'stkuom' => 'PZ',
            ]
        );

        return $partnumberNew;
    }


    public function createOrUpdatePartNumberStock($partnumberid,$quantity, $shipment)
    {
        $partnumberStock = StorageEvidenceStock::where('shipmentid', '=', $shipment->recordid)
            ->where('partnumberid', '=', $partnumberid->id)->first();
        if (empty($partnumberStock)) {
            StorageEvidenceStock::create([
                'centerid' => $shipment->centerid,
                'warehouseid' => $shipment->warehouseid,
                'shipmentid' => $shipment->recordid,
                'partnumberid' => $partnumberid->id,
                'quantity' => floatval($quantity),
            ]);
        } else {
            $partnumberStock->quantity = $partnumberStock->quantity + floatval($quantity);
            $partnumberStock->save();
        }
    }
}
