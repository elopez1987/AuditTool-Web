<?php

namespace App\Traits\Yard;

use App\Models\Shipments\ScheduleInverse\StorageShipmentSchedule;
use App\Models\Shipments\StorageIncidenceJourney;
use App\Models\Shipments\StorageIncidenceJourneyDetails;
use App\Models\Shipments\StorageScheduleInverse;
use App\Models\Shipments\StorageScheduleInverseInventory;
use App\Models\Shipments\StorageScheduleInverseOperation;
use App\Models\Shipments\StorageScheduleInverseTraffic;
use Auth;

trait InsertSchedulleInverse
{

    public function insertSchedulleInverse($request, $receiptunit)
    {
        $results = StorageShipmentSchedule::select([
            'storageincidencejourneys.centerid',
            'storageincidencejourneys.warehouseid',
            'storageincidencejourneys.orderid'
        ])
            ->leftJoin('storageshipmentsscheduledetail', function ($join) {
                $join->on('storageshipmentsschedule.id', '=', 'storageshipmentsscheduledetail.shipmentschedulleid')
                    ->on('storageshipmentsschedule.centerid', '=', 'storageshipmentsscheduledetail.centerid');
            })
            ->leftJoin('storageincidencejourneys', 'storageshipmentsscheduledetail.incidencejourneyid', '=', 'storageincidencejourneys.folioid')
            ->where('storageshipmentsschedule.id', $request['shipmentid'])
            ->get();
// Muestra los resultados
        if (!empty($result)) {
            foreach ($results as $result) {
                $incidence = $this->getIncidenceJourney($result->orderid);
                $deliveries = $this->getIncidenceJourneyDetails($incidence);
                $inverse = $this->createNewInverse($incidence, $receiptunit);
                if (!empty($deliveries)) {
                    foreach ($deliveries as $delivery) {
                        $this->createRegisterTraffic($incidence, $inverse, $delivery);
                        $this->createRegisterInventory($incidence, $inverse, $delivery);
                    }
                }
                $this->createRegisterOperation($incidence, $inverse);
            }
        }
    }

    public function createNewInverse($incidence, $receiptunit)
    {
        return StorageScheduleInverse::updateOrCreate([
            'centerid' => $incidence->centerid,
            'warehouseid' => $incidence->warehouseid,
            'orderid' => $incidence->orderid,
            'folionumber' => $receiptunit->id,
        ]);
    }


    public function createRegisterOperation($incidence, $inverse): void
    {
        StorageScheduleInverseOperation::updateOrCreate(
            [
                'centerid' => $incidence->centerid,
                'warehouseid' => $incidence->warehouseid,
                'orderid' => $incidence->orderid,
                'inverseid' => $inverse->id,
            ]);
    }

    /**
     * @param $incidence
     * @param $inverse
     * @param $delivery
     * @return void
     */
    public function createRegisterInventory($incidence, $inverse, $delivery): void
    {
        StorageScheduleInverseInventory::updateOrCreate(
            [
                'centerid' => $incidence->centerid,
                'warehouseid' => $incidence->warehouseid,
                'orderid' => $incidence->orderid,
                'inverseid' => $inverse->id,
                'delivery' => $delivery->delivery,
            ]);
    }

    /**
     * @param $incidence
     * @param $inverse
     * @param $delivery
     * @return void
     */
    public function createRegisterTraffic($incidence, $inverse, $delivery): void
    {
        StorageScheduleInverseTraffic::updateOrCreate([
            'centerid' => $incidence->centerid,
            'warehouseid' => $incidence->warehouseid,
            'orderid' => $incidence->orderid,
            'inverseid' => $inverse->id,
            'delivery' => $delivery->delivery,
        ], [
            'username' => Auth::user()->username
        ]);
    }


    public function getIncidenceJourney($orderid)
    {
        return StorageIncidenceJourney::controlAccessUser()->where('orderid', $orderid)
            ->select(['centerid', 'warehouseid', 'orderid', 'folioid'])->first();
    }


    public function getIncidenceJourneyDetails($incidence)
    {
        return StorageIncidenceJourneyDetails::controlAccessUser()
            ->where('orderid', $incidence->orderid)->select('delivery')->get();
    }


}
