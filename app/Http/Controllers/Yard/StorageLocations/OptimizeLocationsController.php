<?php

namespace App\Http\Controllers\Yard\StorageLocations;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\ShipmentHeader;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageLocation;
use App\Models\Yard\StorageZone\StorageZone;
use Illuminate\Support\Facades\DB;

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
        $totalLocations = DB::table('storagelocations as s')
            ->leftJoin('storagezones as z', 's.zoneid', '=', 'z.id')
            ->whereNotIn('z.zoneid', ['ST'])
            ->whereNotIn('s.name', ['DUMMY'])
            ->whereNull('s.deleted_at')
            ->groupBy('s.zoneid')
            ->selectRaw('s.zoneid, COUNT(s.locationid) as total_ubicaciones')
            ->get();

        foreach ($totalLocations as $totalLocation) {
            StorageZone::where('id', $totalLocation->zoneid)
                ->update(['capacity' => $totalLocation->total_ubicaciones]);
        }

        return $this->redirectBackSuccess();
    }

    public function UpdateLocationToEnabled(): void
    {
        StorageLocation::controlAccessUser()->update(['statusid' => 'E', 'auxiliary01' => '']);
    }
}
