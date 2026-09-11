<?php

namespace App\Services\Shipment;

use App\Models\Shipments\ShipmentContainer;
use App\Models\Shipments\ShipmentContainerCheck;
use App\Models\Shipments\ShipmentContainerMoveBox;
use App\Models\Shipments\ShipmentStock;
use App\Traits\ResponseApi;

class ServicePick
{
    use ResponseApi;

    public function saveMoveBoxToLpn($request, $shipment, $lpn)
    {
        //      Restamos la cantidad al lpn original
        $lpn->quantity -= $request['quantity'];
        $lpn->save();
        $this->saveLogMoveBox($shipment, $request, 'DE', $request['lpnid']);

        //      Sumamos la cantidad al lpn que recibe o lo crea si no exite el número de parte
        $lpnRec = ShipmentStock::where('orderid', $shipment->id)->where('lpnid', $request['lpnidrec'])
            ->where('partnumberid', $request['partnumberid'])->first();
        if (empty($lpnRec)) {
            $this->createNewStock($shipment, $request);
        } else {
            $lpnRec->quantity += $request['quantity'];
            $lpnRec->save();
        }
        $this->saveLogMoveBox($shipment, $request, 'IN', $request['lpnidrec']);
        $this->saveChildToLpn($shipment, $request);
        $this->deleteContainerStockClear($shipment, $request);
        $this->deleteContainer($request['lpnid']);
    }

    public function deleteContainer($lpn)
    {
        $containerStock = ShipmentStock::where('lpnid', $lpn)->first();
        if (empty($containerStock)) {
            $container = ShipmentContainer::controlAccessUser()
                ->where('lpnid', $lpn)->first();
            $container->delete();
        }
    }

    public function deleteContainerStockClear($shipment, $request)
    {
        $lpnRecMod = ShipmentStock::where('orderid', $shipment->id)
            ->where('lpnid', $request['lpnid'])
            ->where('partnumberid', $request['partnumberid'])->first();
        if (! empty($lpnRecMod)) {
            if ($lpnRecMod->quantity === 0) {
                $lpnRecMod->delete();
            }
        }
    }

    public function createNewStock($shipment, $request): void
    {
        ShipmentStock::create([
            'centerid' => $shipment->centerid,
            'warehouseid' => $shipment->warehouseid,
            'orderid' => $shipment->id,
            'partnumberid' => $request['partnumberid'],
            'quantity' => $request['quantity'],
            'lpnid' => $request['lpnidrec'],
        ]);
    }

    public function saveChildToLpn($shipment, $request)
    {
        $container = $this->getShipmentContainer($shipment, $request['lpnid']);
        ShipmentContainerCheck::updateOrCreate([
            'orderid' => $shipment->id,
            'containerid' => $container->id,
            'lpnid' => $request['lpnid'],
            'lpnchild' => $request['lpnidrec'],
        ]);
    }

    public function getShipmentContainer($shipment, $lpnid): ShipmentContainer
    {
        return ShipmentContainer::where('orderid', $shipment->id)
            ->where('lpnid', $lpnid)->first();
    }

    public function saveLogMoveBox($shipment, $request, $type, $lpn)
    {
        $container = $this->getShipmentContainer($shipment, $lpn);
        ShipmentContainerMoveBox::create([
            'orderid' => $shipment->id,
            'containerid' => $container->id,
            'lpnid' => $lpn,
            'partnumberid' => $request['partnumberid'],
            'quantity' => $request['quantity'],
            'type' => $type,
        ]);
    }
}
