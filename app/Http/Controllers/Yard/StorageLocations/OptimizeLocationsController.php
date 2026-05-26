<?php

namespace App\Http\Controllers\Yard\StorageLocations;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\ShipmentHeader;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageLocation;
use App\Models\Yard\StorageZone\StorageZone;

class OptimizeLocationsController extends BaseController
{
    public function optimizelocations()
    {
        $this->UpdateLocationToEnabled();

        // Bloquea cortinas: actualiza ubicaciones con unidades activas
        $units = ReceiptUnit::controlAccessUser()
            ->whereNotIn('statusid', ['TR'])
            ->whereNotNull('locationid')
            ->select(['locationid', 'boxnumber'])
            ->get()
            ->keyBy('locationid');

        foreach ($units as $locationId => $unit) {
            StorageLocation::controlAccessUser()
                ->where('id', $locationId)
                ->update(['statusid' => 'D', 'auxiliary01' => $unit->boxnumber]);
        }

        // Bloquea precargas: actualiza ubicaciones con embarques activos
        $shipmentLocationIds = ShipmentHeader::controlAccessUser()
            ->leftJoin('orderdeliveries', 'orderdeliveries.id', '=', 'shipmentheader.orderid')
            ->whereNotIn('orderdeliveries.statusid', ['DH', 'CA'])
            ->whereNotNull('shipmentheader.locationid')
            ->pluck('shipmentheader.locationid');

        if ($shipmentLocationIds->isNotEmpty()) {
            StorageLocation::controlAccessUser()
                ->whereIn('id', $shipmentLocationIds)
                ->update(['statusid' => 'D']);
        }

        // Actualiza capacidades por zona
        $totalLocations = StorageLocation::controlAccessUser()
            ->Join('storagezones as z', 'storagelocations.zoneid', '=', 'z.id')
            ->whereNotIn('z.zoneid', ['ST'])
            ->whereNotIn('storagelocations.name', ['DUMMY'])
            ->whereNull('storagelocations.deleted_at')
            ->whereNull('z.deleted_at')
            ->groupBy('storagelocations.zoneid')
            ->selectRaw('storagelocations.zoneid, COUNT(storagelocations.locationid) as total_ubicaciones')
            ->get();

        foreach ($totalLocations as $totalLocation) {
            StorageZone::where('id', $totalLocation->zoneid)
                ->update(['capacity' => $totalLocation->total_ubicaciones]);
        }


        $receiptUnits = ReceiptUnit::controlAccessUser()
            ->whereNotIn('statusid', ['TR'])
            ->whereNotNull('locationid')
            ->select(['id', 'zoneid', 'locationid'])
            ->get();

        $locationIds = $receiptUnits->pluck('locationid')->unique();

        $locations = StorageLocation::controlAccessUser()
            ->whereIn('id', $locationIds)
            ->select(['id', 'zoneid'])
            ->get()
            ->keyBy('id');

        foreach ($receiptUnits as $receiptUnit) {
            $location = $locations->get($receiptUnit->locationid);

            if ($location) {
                $receiptUnit->update(['zoneid' => $location->zoneid]);
            }
        }

        return $this->redirectBackSuccess();
    }

    public function UpdateLocationToEnabled(): void
    {
        StorageLocation::controlAccessUser()
            ->update(['statusid' => 'E', 'auxiliary01' => '']);
    }
}
