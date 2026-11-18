<?php

namespace App\Http\Controllers\Receipt;

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
        $warehouses = $this->getWarehouseForSelect();

        return view('Receipt.Reports.reportreceipts', compact('centers', 'warehouses'));
    }

    public function generateFileToExportReceipt(ReportReceiptRequest $request)
    {
        if ($request['type'] === 'RG') {
            return Excel::download(
                new ReportReceipt($request),
                'Reporte de descargas.xlsx'
            )->deleteFileAfterSend();
        }
    }
}
