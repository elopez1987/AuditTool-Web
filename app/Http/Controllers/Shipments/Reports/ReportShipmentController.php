<?php

namespace App\Http\Controllers\Shipments\Reports;

use App\Exports\Shipment\Events\ReportShipmentEventsExport;
use App\Exports\Shipment\Evidence\ExportReportPhotosForShipment;
use App\Exports\Shipment\IncidencesJourneys\ExportIncidencesJourneys;
use App\Exports\Shipment\Inverse\ExportReportBitacoraLogInverse;
use App\Exports\Shipment\Inverse\ReportCheckInverse;
use App\Exports\Shipment\OrderDelivery\GeneralReportShipment;
use App\Exports\Shipment\OrderDelivery\ReporteEvidenciasFacturas;
use App\Exports\Shipment\Pallets\ExportReportPallets;
use App\Exports\Shipment\PODS\ExportReportShipmentPODs;
use App\Exports\Shipment\Seals\ReportShipmentSeals;
use App\Exports\Shipment\VerifyProcess\ExportReportDetailShipmentChecker;
use App\Exports\Shipment\VerifyProcess\ExportReportLpnsCheckers;
use App\Exports\Shipment\VerifyProcess\ReportCheckForShipments;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportShipmentController extends BaseController
{
    public function index()
    {
        $centers = $this->getCenterForSelect();
        $warehouses = $this->getWarehouseForSelect();

        return view('Shipments.Reports.index', compact('centers', 'warehouses'));
    }

    public function exportreportshipments(Request $request)
    {
        if ($request['type'] === 'RPG') {
            return Excel::download(new GeneralReportShipment($request), 'Reporte de embarques.xlsx')->deleteFileAfterSend();
        }
        if ($request['type'] === 'RPS') {
            return Excel::download(new ReportShipmentSeals($request), 'Reporte de sellos.xlsx')->deleteFileAfterSend();
        }
        if ($request['type'] === 'RPE') {
            return Excel::download(new ReportShipmentEventsExport($request), 'Reporte de eventos en embarques.xlsx')->deleteFileAfterSend();
        }
        if ($request['type'] === 'RPF') {
            return Excel::download(new ExportReportPhotosForShipment($request), 'Reporte de fotos en embarque.xlsx')->deleteFileAfterSend();
        }
        if ($request['type'] === 'RIP') {
            return Excel::download(new ReportCheckInverse($request), 'Reporte de reingreso de producto.xlsx')->deleteFileAfterSend();
        }
        if ($request['type'] === 'BLI') {
            return Excel::download(new ExportReportBitacoraLogInverse($request), 'Bitacora Log Inversa.xlsx')->deleteFileAfterSend();
        }
        if ($request['type'] === 'LPV') {
            return Excel::download(new ExportReportLpnsCheckers($request), 'Lpns Verificados.xlsx')->deleteFileAfterSend();
        }
        if ($request['type'] === 'LDV') {
            return Excel::download(new ExportReportDetailShipmentChecker($request), 'Detalle de verificación.xlsx')->deleteFileAfterSend();
        }
        if ($request['type'] === 'REC') {
            return Excel::download(new ReporteEvidenciasFacturas($request), 'Embarques sin evidencia de facturas.xlsx')->deleteFileAfterSend();
        }
        if ($request['type'] === 'INC') {
            return Excel::download(new ExportIncidencesJourneys($request), 'Reporte de incidencias en embarques.xlsx')->deleteFileAfterSend();
        }
        if ($request['type'] === 'POD') {
            return Excel::download(new ExportReportShipmentPODs($request), 'Reporte de PODs.xlsx')->deleteFileAfterSend();
        }
        if ($request['type'] === 'RVS') {
            return Excel::download(new ReportCheckForShipments($request), 'Reporte de verificación por embarques.xlsx')->deleteFileAfterSend();
        }
        if ($request['type'] === 'PLT') {
            return Excel::download(new ExportReportPallets($request), 'Reporte de pallets embarcados.xlsx')->deleteFileAfterSend();
        }
    }
}
