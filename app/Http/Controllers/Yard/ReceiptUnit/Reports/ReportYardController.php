<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\Reports;

use App\Exports\Receipt\CircuitPlant\ExportReportCircuitPlant;
use App\Exports\Receipt\CircuitPlant\ReportUnitCircuitTravel;
use App\Exports\Yard\ReceiptUnit\ReportUnitEvidences;
use App\Exports\Yard\ReceiptUnit\StorageBoxStockExport;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\Reports\FilterReportYardRequest;
use Maatwebsite\Excel\Facades\Excel;

class ReportYardController extends BaseController
{
    public function index()
    {
        $centers = $this->getCenterForSelect();

        return view('Yards.Reports.index', compact('centers'));
    }

    public function exportreportyards(FilterReportYardRequest $request)
    {
        if ($request['type'] === 'RTE') {
            return Excel::download(
                new StorageBoxStockExport($request['workdayin'], $request['workdayout']),
                'Reporte de tiempos de unidades.xlsx'
            )->deleteFileAfterSend();
        }
        if ($request['type'] === 'RCI') {
            return Excel::download(
                new ReportUnitCircuitTravel($request['workdayin'], $request['workdayout']),
                'Reporte de tiempos de circuito.xlsx'
            )->deleteFileAfterSend();
        }
        if ($request['type'] === 'REV') {
            return Excel::download(
                new ReportUnitEvidences($request['workdayin'], $request['workdayout']),
                'Reporte de evidencias de unidades.xlsx'
            )->deleteFileAfterSend();
        }
        if ($request['type'] === 'RCP') {
            return Excel::download(
                new ExportReportCircuitPlant($request['centerid'], $request['workdayin'], $request['workdayout']),
                'Reporte de entradas planta.xlsx'
            )->deleteFileAfterSend();
        }
    }
}
