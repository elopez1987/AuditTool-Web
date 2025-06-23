<?php

namespace App\Http\ControllersApp\Shipment\VerificationProcess;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Shipment\VerificationProcess\SaveProcessCountRequest;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentContainer;
use App\Models\Shipments\ShipmentControl;
use App\Models\Shipments\ShipmentStock;
use App\Traits\ResponseApi;
use Illuminate\Support\Facades\DB;

class SaveProcessVerificationCountController extends Controller
{
    use ResponseApi;

    public function index(SaveProcessCountRequest $request)
    {
        DB::beginTransaction();
        try {
            $orderDelivery = OrderDeliverie::controlAccessUser()->where('shipmentid', $request['shipmentid'])->first();
            $sku = InventoryPartnumber::searchPartNumber($request);
            self::createNewVerifyControlToShipment($orderDelivery, $sku, $request['quantity']);
            self::createNewContainerForShipment($orderDelivery, $request['lpnid']);
            self::saveCountCheckShipment($orderDelivery, $sku, $request);
            $total = $this->getTotalShipmentControl($orderDelivery);
            DB::commit();
            return $this->getResponseSuccess('saveCheckShipment', $total);
        } catch (Exception) {
            DB::rollback();
            return $this->getResponseWithErrors('saveCheckShipment', 'Error al procesar la solicitud, intente mas tarde.');
        }
    }

    static function saveCountCheckShipment($orderDelivery, $sku, $request)
    {
        ShipmentStock::create([
            'centerid' => $orderDelivery->centerid,
            'warehouseid' => $orderDelivery->warehouseid,
            'orderid' => $orderDelivery->id,
            'partnumberid' => $sku->id,
            'quantity' => $request['quantity'],
            'lpnid' => $request['lpnid']
        ]);
    }


    static function createNewContainerForShipment($orderDelivery, $lpnid)
    {
        ShipmentContainer::updateOrCreate([
            'centerid' => $orderDelivery->centerid,
            'warehouseid' => $orderDelivery->warehouseid,
            'orderid' => $orderDelivery->id,
            'customerid' => $orderDelivery->customerid,
            'lpnid' => $lpnid
        ]);
    }


    static function createNewVerifyControlToShipment($orderDelivery, $sku, $quantity)
    {
        $shipmentControl = self::getShipmentControl($orderDelivery->id, $sku->id);
        if ($shipmentControl->quantitytrue == '') {
            $shipmentControl->quantitytrue = $quantity;
        } else {
            $shipmentControl->quantitytrue = $shipmentControl->quantitytrue + $quantity;
        }
        $shipmentControl->quantityfail = $shipmentControl->quantityrequested - $shipmentControl->quantitytrue;
        $shipmentControl->save();
    }


    static function getShipmentControl($orderDelivery, $partnumberid)
    {
        return ShipmentControl::controlAccessUser()->where('orderid', '=', $orderDelivery)
            ->where('partnumberid', '=', $partnumberid)
            ->select(['recordid', 'partnumberid', 'quantitytrue',
                'quantityfail', 'quantityrequested'])->first();
    }


    public function getTotalShipmentControl(OrderDeliverie $orderDelivery)
    {
        return ShipmentControl::controlAccessUser()->select([
            DB::raw('sum(shipmentcontrol.quantityrequested) as totalpedido'),
            DB::raw('(select sum(quantity) from shipmentstock where shipmentstock.orderid = shipmentcontrol.orderid) as verificado')
        ])->where('shipmentcontrol.orderid',$orderDelivery->id)
            ->groupBy('shipmentcontrol.orderid')->get();
    }


}
