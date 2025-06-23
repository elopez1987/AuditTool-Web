<?php

namespace App\Http\ControllersApp\Inverse;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Inverse\SearchShipmentRequest;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\StorageIncidenceJourney;
use App\Models\Shipments\StorageIncidenceJourneyDetails;
use App\Models\Shipments\StorageScheduleInverse;
use App\Models\Shipments\StorageScheduleInverseOperation;
use App\Traits\ResponseApi;
use Carbon\Carbon;

class SearchShipmentController extends Controller
{
    use ResponseApi;

    public function getShipmentInverse(SearchShipmentRequest $request)
    {
        $shipment = $this->searchShipmentInOrderDeliveries($request['shipmentid']);
        $incidence = $this->searchIncidenceJourney($shipment);
        if (empty($incidence)) {
            return $this->getResponseWithErrors('getShipmentInverse', 'El número de embarque no cuenta con registro de incidencia.');
        }
        $inverse = $this->searchShipmentInScheduleInverse($shipment);
        if (empty($inverse)) {
            return $this->getResponseWithErrors('getShipmentInverse', 'No existe número de incidencia o se encuentra terminado.');
        }
        $statusReceipt = $this->searchShipmentInInverseOperation($inverse);
        if (!empty($statusReceipt)) {
            if ($statusReceipt->finishdate != '') {
                return $this->getResponseWithErrors('getShipmentInverse', 'El proceso de recibo ya se encuentra terminado.');
            }
        }
        $this->creteRegisterStartCheck($shipment, $inverse);
        $deliveries = $this->getDeliveriesInJourneyDetails($shipment);
        return $this->getResponseSuccess('getShipmentInverse', ['shipmentid' => $shipment->shipmentid, 'deliveryid' => $deliveries]);
    }

    public function creteRegisterStartCheck($shipment, $inverse): void
    {
        StorageScheduleInverseOperation::updateOrCreate(
            [
                'centerid' => $shipment->centerid,
                'warehouseid' => $shipment->warehouseid,
                'orderid' => $shipment->id,
                'inverseid' => $inverse->id,
            ],
            [
                'startdate' => Carbon::now(),
            ]
        );
    }

    public function searchShipmentInOrderDeliveries($shipmentid)
    {
        return OrderDeliverie::controlAccessUser()
            ->where('statusid', 'DH')
            ->where('shipmentid', '=', $shipmentid)->first();
    }

    public function searchIncidenceJourney($shipment)
    {
        return StorageIncidenceJourney::controlAccessUser()
            ->where('orderid', $shipment->id)->first();
    }

    /**
     * @param $shipment
     * @return array
     */
    public function getDeliveriesInJourneyDetails($shipment): array
    {
        return StorageIncidenceJourneyDetails::controlAccessUser()
            ->where('orderid', '=', $shipment->id)
            ->pluck('delivery')->toArray();
    }

    public function searchShipmentInScheduleInverse($shipment)
    {
        return StorageScheduleInverse::where('orderid', $shipment->id)
            ->where('statusid','AD')->first();
    }

    public function searchShipmentInInverseOperation(StorageScheduleInverse $inverse)
    {
        return StorageScheduleInverseOperation::where('orderid', $inverse->orderid)->select('finishdate')->first();
    }
}
