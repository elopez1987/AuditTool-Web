<?php

namespace App\Http\Services\Yard;

use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentStage;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageLocation;
use App\Traits\ResponseApi;
use DB;
use Illuminate\Support\Collection;
use LaravelIdea\Helper\App\Models\Yard\_IH_ReceiptUnit_C;

class AssingDoorToUnitFromShipment
{
    use ResponseApi;

    public function addDoorToUnit($receiptunit)
    {
        $shipmentid = $receiptunit->shipmentid;
        $order = $this->getOrderDeliverie($receiptunit);
        if (! empty($order)) {
            $doors = $this->getDoorEnabled($shipmentid, $receiptunit->centerid);
            foreach ($doors as $door) {
                if ($door->statusid === 'E') {
                    $doorAssing = ShipmentStage::where('orderid', '=', $order->id)->first();
                    if (empty($doorAssing)) {
                        $unitUpdate = $this->updateUnit($receiptunit, $door);
                        $this->createAssingDoor($order, $door);
                        $this->updateLocation($receiptunit->centerid, $door, $unitUpdate);

                        return $unitUpdate;
                    }
                }
            }
        }
    }

    public function updateLocation(string $center, $door, $unitUpdate): void
    {
        StorageLocation::where('centerid', '=', $center)
            ->where('id', $door->doorid)
            ->update(['statusid' => 'A', 'auxiliary01' => $unitUpdate->boxnumber]);
    }

    public function createAssingDoor(OrderDeliverie $order, $door): void
    {
        ShipmentStage::create([
            'orderid' => $order->id,
            'value01' => $door->doorid,
        ]);
    }

    /**
     * @return ReceiptUnit|array<ReceiptUnit>|_IH_ReceiptUnit_C|null
     */
    public function updateUnit($receiptunit, $door)
    {
        $unitUpdate = ReceiptUnit::find($receiptunit->id);
        $unitUpdate->zoneid = $door->zoneid;
        $unitUpdate->locationid = $door->doorid;
        $unitUpdate->statusid = 'RS';
        $unitUpdate->save();

        return $unitUpdate;
    }

    public function getDoorEnabled($shipmentid, string $center): Collection
    {
        return collect(DB::select('
           select a.stageid,a.doorid,b.zoneid,b.statusid from storagedoorstages a
                left join storagelocations b on a.doorid = b.id and a.centerid = b.centerid
                where a.stageid in ( select  locationid
                from shipmentheader  where orderid in (select id from orderdeliveries
                                                                 where shipmentid=? and centerid=?))
                and a.centerid=?
           ', [$shipmentid, $center, $center]));
    }

    /**
     * @param  $shipmentid
     * @return OrderDeliverie
     */
    public function getOrderDeliverie($receiptunit)
    {
        return OrderDeliverie::where('centerid', '=', $receiptunit->centerid)
            ->where('shipmentid', '=', $receiptunit->shipmentid)
            ->select(['id', 'shipmentid', 'statusid'])
            ->whereIn('statusid', ['VF', 'PC'])->first();
    }
}
