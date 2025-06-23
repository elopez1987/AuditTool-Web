<?php

namespace App\Http\ControllersApp\Shipment\VerificationProcess;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Shipment\VerificationProcess\UnpickRequest;
use App\Http\Services\Shipment\ServicePick;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentControl;
use App\Models\Shipments\ShipmentStock;
use App\Traits\ResponseApi;

class UnPickLpnController extends Controller
{
    use ResponseApi;

    private ServicePick $servicePick;

    public function __construct(ServicePick $servicePick)
    {
        $this->servicePick = $servicePick;
    }


    public function index(UnpickRequest $request)
    {

        $shipment = $this->getShipmentFromOrderDelivery($request['shipmentid']);
        $shipmentControl = ShipmentControl::controlAccessUser()->where('orderid', $shipment->id)
            ->where('partnumberid', $request['partnumberid'])->first();
        $shipmentStock = ShipmentStock::where('lpnid', $request['lpnid'])
            ->where('partnumberid', $request['partnumberid'])->first();

        if (empty($shipmentStock)) {
            return $this->getResponseWithErrors('saveunpicklpn', 'No existe el número de lpn ingresado, favor de validar.');
        }

        if ($request['quantitystock'] < $request['quantity']) {
            return $this->getResponseWithErrors('saveunpicklpn', 'La cantidad a mover no puede ser mayor a la existencia.');
        }

        $shipmentStock->quantity = $shipmentStock->quantity - $request['quantity'];
        $shipmentStock->save();

        $shipmentControl->quantitytrue = $shipmentControl->quantitytrue - $request['quantity'];
        $shipmentControl->quantityfail = $shipmentControl->quantityfail + $request['quantity'];
        $shipmentControl->save();

        $this->servicePick->deleteContainerStockClear($shipment, $request);
        $this->servicePick->deleteContainer($request['lpnid']);
        return $this->getResponseWithSuccess('saveunpicklpn', 'Cajas desasignadas con exito.');
    }


    public function getShipmentFromOrderDelivery($shipmentid)
    {
        return OrderDeliverie::controlAccessUser()->where('shipmentid', $shipmentid)
            ->whereNotIn('statusid', ['DH', 'CA'])->first();
    }


}
