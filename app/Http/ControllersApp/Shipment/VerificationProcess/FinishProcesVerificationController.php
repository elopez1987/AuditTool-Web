<?php

namespace App\Http\ControllersApp\Shipment\VerificationProcess;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Shipment\VerificationProcess\SearchShipmenVerificationRequest;
use App\Http\Services\ServiceShipmentControl;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentCheck;
use App\Traits\ResponseApi;
use Illuminate\Support\Facades\DB;

class FinishProcesVerificationController extends Controller
{
    use ResponseApi;

    public function index(SearchShipmenVerificationRequest $request)
    {
        DB::beginTransaction();
        try {
            $orderDelivery = OrderDeliverie::controlAccessUser()->where('shipmentid', $request['shipmentid'])->first();
            if (empty($orderDelivery)) {
                return $this->getResponseWithErrors('saveFinishCheckShipment', 'El número de embarque no existe o se encuentra en estatus no permitido.');
            }
            $shipmentCheck = $this->updateShipmentHeaderStatus($orderDelivery);
            DB::commit();
            return $this->getResponseSuccess('saveFinishCheckShipment', $shipmentCheck);
        } catch (Exception) {
            DB::rollback();
            return $this->getResponseWithErrors('saveFinishCheckShipment', 'Error al procesar la solicitud, intente mas tarde.');
        }
    }

    /**
     * @param OrderDeliverie $orderDelivery
     * @return ShipmentCheck
     */
    public function updateShipmentHeaderStatus(OrderDeliverie $orderDelivery): ShipmentCheck
    {
        $shipmentCheck = ShipmentCheck::controlAccessUser()->where('orderid', '=', $orderDelivery->id)->first();
        $shipmentCheck->statusid = 'VF';
        $shipmentCheck->finishdate = now();
        $shipmentCheck->save();

        if ($orderDelivery->statusid != 'FO' || $orderDelivery->statusid != 'CA' || $orderDelivery->statusid != 'DH' || $orderDelivery->statusid != 'EJ' || $orderDelivery->statusid != 'IF') {
            $orderDelivery->statusid = 'VF';
            $orderDelivery->save();
        }

        return $shipmentCheck;
    }


}
