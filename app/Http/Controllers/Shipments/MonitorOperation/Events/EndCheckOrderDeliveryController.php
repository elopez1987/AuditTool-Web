<?php

namespace App\Http\Controllers\Shipments\MonitorOperation\Events;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentDataOperation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EndCheckOrderDeliveryController extends BaseController
{
    public function index($id)
    {
 
        DB::beginTransaction();
        try {
            $this->updateShipmentDataOperation($id);
            $this->updateStatusOrderDelivery($id);
            DB::commit();
           return  $this->redirectBackSuccess();
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * @param $id
     * @return void
     */
    public function updateStatusOrderDelivery($id): void
    {
        $orderdelivery = OrderDeliverie::controlAccessUser()->whereId($id)->first();
        $orderdelivery->statusid = 'IV';
        $orderdelivery->save();
    }

    /**
     * @param $id
     * @return void
     */
    public function updateShipmentDataOperation($id): void
    {
        $shipment = ShipmentDataOperation::controlAccessUser()->where('orderid', $id)->first();
        $shipment->endchecker = now();
        $shipment->lastuserid = Auth::user()->id;
        $shipment->save();
    }


}
