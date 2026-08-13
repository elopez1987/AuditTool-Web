<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\Reports;

use App\Exports\Receipt\CircuitPlant\ExportReportCircuitPlant;
use App\Exports\Receipt\CircuitPlant\ReportUnitCircuitTravel;
use App\Exports\Yard\ReceiptUnit\ReportUnitEvidences;
use App\Exports\Yard\ReceiptUnit\StorageBoxStockExport;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\Reports\FilterReportYardRequest;
use App\Models\Yard\Carriers\ShipmentCarrier;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageReason;
use App\Models\Yard\StorageUnitType;
use Maatwebsite\Excel\Facades\Excel;

class ReportYardController extends BaseController
{
    public function index()
    {
        $centers = $this->getCenterForSelect();
        $carriersFilter = $this->getCarrierForUser();
        $warehouses = $this->getWarehouseForSelect();

        $carriers = ShipmentCarrier::controlAccessUser()
            ->whereStatusid('E')
            ->Carriers($carriersFilter)
            ->whereIn('id', ReceiptUnit::controlAccessUser()
                ->select('carrierid')->distinct())
            ->select(['name', 'id'])->get();

        $reasons = StorageReason::where('type', 'IN')->select(['id', 'name'])->get();
        $origins = StorageUnitType::controlAccessUser()
            ->where('statusid', 'E')->select(['id', 'description'])->get();

        return view('Yards.Reports.index',
            compact('centers', 'carriers', 'reasons', 'origins', 'warehouses'));
    }

    public function exportreportyards(FilterReportYardRequest $request)
    {
        if ($request['type'] === 'RTE') {
            return Excel::download(
                new StorageBoxStockExport($request), 'Reporte de tiempos de unidades.xlsx', )->deleteFileAfterSend();
        }
        if ($request['type'] === 'RCI') {
            return Excel::download(
                new ReportUnitCircuitTravel($request['workdayin'], $request['workdayout']),
                'Reporte de tiempos de circuito.xlsx',
            )->deleteFileAfterSend();
        }
        if ($request['type'] === 'REV') {
            return Excel::download(
                new ReportUnitEvidences($request), 'Reporte de evidencias de unidades.xlsx',
            )->deleteFileAfterSend();
        }
        if ($request['type'] === 'RCP') {
            return Excel::download(
                new ExportReportCircuitPlant($request['centerid'], $request['workdayin'], $request['workdayout']),
                'Reporte de entradas planta.xlsx',
            )->deleteFileAfterSend();
        }
    }
}
