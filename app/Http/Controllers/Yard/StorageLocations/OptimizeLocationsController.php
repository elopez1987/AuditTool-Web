<?php

namespace App\Http\Controllers\Yard\StorageLocations;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\ShipmentHeader;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageLocation;

class OptimizeLocationsController extends BaseController
{
    public function optimizelocations()
    {
        $this->UpdateLocationToEnabled();
        $locations = $this->getLocations();
        $units = $this->getReceiptUnits();
        $shipments = $this->getShipmentHeaders();
        foreach ($locations as $location) {
            foreach ($units as $unit) {
                if ($location->id === $unit->locationid) {
                    $location->auxiliary01 = $unit->boxnumber;
                    $location->statusid = 'D';
                    $location->save();
                }
            }
        }
        foreach ($shipments as $shipment) {
            foreach ($locations as $location) {
                if ($location->id === $shipment->locationid) {
                    $location->statusid = 'D';
                    $location->save();
                }
            }
        }
        return $this->redirectBackSuccess();
    }

    public function getShipmentHeaders()
    {
        return ShipmentHeader::controlAccessUser()
            ->leftJoin('orderdeliveries', 'orderdeliveries.id', '=', 'shipmentheader.orderid')
            ->whereNotIn('orderdeliveries.statusid', ['DH', 'CA'])
            ->whereNotNull('shipmentheader.locationid')
            ->select(['shipmentheader.id', 'shipmentheader.locationid'])->get();
    }

    public function getReceiptUnits()
    {
        return ReceiptUnit::controlAccessUser()->whereNotIn('statusid', ['TR'])
            ->select(['locationid', 'boxnumber'])->get();
    }

    public function getLocations()
    {
        return StorageLocation::controlAccessUser()->select(['id', 'auxiliary01', 'statusid'])->get();
    }

    public function UpdateLocationToEnabled(): void
    {
        StorageLocation::controlAccessUser()->update(['statusid' => 'E', 'auxiliary01' => '']);
    }
}
