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
    //    Ocupación de patio
    public function index()
    {
        // Obtener centros de distribución
        $centers = SystemCenterDistribution::controlAccessUser()
            ->select(['centerid', 'name', 'description', 'capacityyard'])
            ->get();

        // Obtener zonas con eager loading de la relación center
        $zones = StorageZone::controlAccessUser()
            ->with('center:centerid,description')
            ->select(['id', 'name', 'capacity', 'centerid'])
            ->whereRaw("charindex('ZO-01', configurationflag) > 0")
            ->where('statusid', '=', 'E')
            ->get();

        // Obtener ocupación por zona indexado por zoneid
        $locationfill = ReceiptUnit::controlAccessUser()
            ->whereNotIn('statusid', ['TR'])
            ->select(['zoneid', DB::raw('COUNT(zoneid) as ocupado')])
            ->groupBy('zoneid')
            ->pluck('ocupado', 'zoneid');

        // Obtener conteo por estatus de unidad con relación unitstatus
        $unitsByStatus = ReceiptUnit::controlAccessUser()
            ->whereNotIn('statusid', ['TR'])
            ->with('unitstatus:id,name')
            ->select(['unitstatusid', DB::raw('COUNT(*) as total')])
            ->groupBy('unitstatusid')
            ->get()
            ->keyBy('unitstatusid');

        // Agrupar zonas por centro para evitar loops en la vista
        $zonesByCenter = $zones->groupBy('centerid');

        return view(
            'Yards.CapacityYard.index',
            compact('centers', 'zones', 'zonesByCenter', 'locationfill', 'unitsByStatus')
        );
    }

    //    Detalle de ocupación del patio por la placa de la unidad
    public function layoutinventoryforzone()
    {
        $zones = StorageZone::controlAccessUser()->whereStatusid('E')->whereRaw("charindex('ZO-01', configurationflag) > 0")->get();
        $locations = StorageLocation::controlAccessUser()
            ->select(['zoneid', 'id', 'locationid', 'name', 'auxiliary01', 'statusid'])
            ->whereIn('statusid', ['E', 'D'])
            ->get();

        return view('Yards.CapacityYard.inventoryforzone', compact('zones', 'locations'));
    }

    public function exportDataLocationsForStatus()
    {
        return Excel::download(new InventoryLocationExport, 'Inventario de Ubicaciones.xlsx')->deleteFileAfterSend();
    }
}
