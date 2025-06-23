<?php

namespace App\Http\Controllers\Receipt;

use App\Exports\Receipt\ArrivePlan\ExportPlanArriveReceipt;
use App\Exports\Receipt\Incidences\ReportIncidenceReceiptExport;
use App\Exports\Receipt\ReceipPallets\ExportReceiptPallets;
use App\Exports\Receipt\ReceipPallets\ExportReportOfLpnReceipt;
use App\Exports\Receipt\ReceiptAsn\ReportReceipt;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Receipt\Reports\ReportReceiptRequest;
use Maatwebsite\Excel\Facades\Excel;

class ReportReceiptsController extends BaseController
{

    public function reportreceipts()
    {
        $this->authorize('reportreceipts');
        $centers = $this->getCenterForSelect();
        return view('Receipt.Reports.reportreceipts', compact('centers'));
    }

    public function generateFileToExportReceipt(ReportReceiptRequest $request)
    {
        if ($request['type'] == 'RG') {
            return Excel::download(new ReportReceipt($request['workdayin'], $request['workdayout'], $request['centerid']),
                'Reporte de descargas.xlsx')->deleteFileAfterSend();
        }
        if ($request['type'] == 'IN') {
            return Excel::download(new ReportIncidenceReceiptExport($request['workdayin'], $request['workdayout'], $request['centerid']),
                'Reporte de incidencias en descargar.xlsx')->deleteFileAfterSend();
        }

        if ($request['type'] == 'PA') {
            return Excel::download(new ExportReportOfLpnReceipt($request['workdayin'], $request['workdayout'], $request['centerid']),
                'Reporte de lpns en recibo.xlsx')->deleteFileAfterSend();
        }

        if ($request['type'] == 'PR') {
            return Excel::download(new ExportPlanArriveReceipt($request['workdayin'], $request['workdayout'], $request['centerid']),
                'Reporte de arribo de unidades.xlsx')->deleteFileAfterSend();
        }

        if ($request['type'] == 'TR') {
            return Excel::download(new ExportReceiptPallets($request['workdayin'], $request['workdayout'], $request['centerid']),
                'Reporte de tarimas.xlsx')->deleteFileAfterSend();
        }


    }

}
