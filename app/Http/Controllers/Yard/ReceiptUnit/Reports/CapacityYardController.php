<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\Reports;

use App\Exports\Yard\Locations\InventoryLocationExport;
use App\Http\Controllers\BaseController;
use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageLocation;
use App\Models\Yard\StorageZone\StorageZone;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class CapacityYardController extends BaseController
{
    //    Ocupacion de patio
    public function index()
    {
        $zones = StorageZone::controlAccessUser()->select(['id', 'name', 'capacity', 'centerid'])
            ->whereRaw("charindex('ZO-01', configurationflag) > 0")->where('statusid', '=', 'E')->get();
        $totalLocations = StorageZone::controlAccessUser()->select(['capacity'])->where('statusid', '=', 'E')->sum('capacity');
        $locationsBusy = ReceiptUnit::controlAccessUser()->whereNotIn('statusid', ['TR'])->get()->count();
        $centers = SystemCenterDistribution::controlAccessUser()
            ->select(['centerid', 'name', 'description', 'capacityyard'])->get();
        $locationfill = ReceiptUnit::controlAccessUser()
            ->whereNotIn('statusid', ['TR'])
            ->select(['zoneid', DB::raw('COUNT(zoneid) as ocupado')])->groupBy('zoneid')->get();

        return view(
            'Yards.CapacityYard.index',
            compact('centers', 'totalLocations', 'locationsBusy', 'zones', 'locationfill')
        );
    }

    //    Detalle de ocupacion del patio por la placa de la unidad
    public function layoutinventoryforzone()
    {
        $zones = StorageZone::controlAccessUser()->whereStatusid('E')->whereRaw("charindex('ZO-01', configurationflag) > 0")->get();
        $locations = $this->getDataLocationStatus();

        return view('Yards.CapacityYard.inventoryforzone', compact('zones', 'locations'));
    }

    public function exportDataLocationsForStatus()
    {
        return Excel::download(new InventoryLocationExport, 'Inventario de Ubicaciones.xlsx')->deleteFileAfterSend();
    }

    public function getDataLocationStatus()
    {
        return StorageLocation::controlAccessUser()
            ->select(['zoneid', 'id', 'locationid', 'name', 'auxiliary01', 'statusid'])
            ->whereIn('statusid', ['E', 'D'])
            ->get();
    }
}
