<?php

namespace App\Http\ControllersApp\Inverse;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Inverse\SearchShipmentRequest;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\StorageScheduleInverse;
use App\Models\Shipments\StorageScheduleInverseOperation;
use App\Traits\ResponseApi;
use Carbon\Carbon;

class CloseProcessController extends Controller
{
    use ResponseApi;

    public function closeProcessReceiptInverse(SearchShipmentRequest $request)
    {
        $shipment = $this->getShipmentInOrderDeliverie($request['shipmentid']);
        if (empty($shipment)) {
            return $this->getResponseWithErrors('closeReceiptInverse', 'El embarque se encuentra en estatus incorrecto.');
        }
        $inverse = $this->getShipmentInSchedulleInverse($shipment);
        $verif = $this->getSchedulleInverseOperation($shipment);
        if (!empty($verif->finishdate)) {
            return $this->getResponseWithErrors('closeReceiptInverse', 'El proceso se encuentra finalizado,favor de validar.');
        }
        $this->updateCloseProcessReceipt($shipment, $inverse);
        return $this->getResponseSuccess('saveCheckReceiptInverse', []);
    }

    public function getShipmentInOrderDeliverie($shipmentid)
    {
        return OrderDeliverie::controlAccessUser()
            ->where('statusid','DH')
            ->where('shipmentid', '=', $shipmentid)->first();
    }
    public function getShipmentInSchedulleInverse($shipment)
    {
        return StorageScheduleInverse::controlAccessUser()->where('orderid', $shipment->id)->first();
    }

    public function updateCloseProcessReceipt($shipment, $inverse): void
    {
        StorageScheduleInverseOperation::updateOrCreate(
            [
                'centerid' => $shipment->centerid,
                'warehouseid' => $shipment->warehouseid,
                'orderid' => $shipment->id,
                'inverseid' => $inverse->id,
            ],
            [
                'finishdate' => Carbon::now(),
            ]
        );
    }

    public function getSchedulleInverseOperation(OrderDeliverie $shipment)
    {
        return StorageScheduleInverseOperation::controlAccessUser()
            ->where('orderid', $shipment->id)->select('finishdate')->first();
    }


}
