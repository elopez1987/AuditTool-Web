<?php

namespace App\Http\Controllers\Counting;

use App\Exports\Inventory\FiscalInventory\ExportAdjustmentInventory;
use App\Exports\Inventory\FiscalInventory\ExportaInventoryCount;
use App\Exports\Inventory\FiscalInventory\ExportInventoryCountTrans01;
use App\Exports\Inventory\FiscalInventory\ExportInventoryCountTrans02;
use App\Exports\Inventory\FiscalInventory\ExportReportChepCount;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Excel;

class ExportReportCountController extends Controller
{
    public function exportcountchep($id)
    {
        $inventoryid = $id;
        return (new ExportReportChepCount($inventoryid))
            ->download('Conteo de tarimas chep.csv',
                Excel::CSV, ['Content-Type' => 'text/csv',
                ])->deleteFileAfterSend();
    }

    public function exportadjustmentinventory($id)
    {
        $inventoryid = $id;
        return (new ExportAdjustmentInventory($inventoryid))
            ->download('Kardex de ajustes de inventario.csv',
                Excel::CSV, ['Content-Type' => 'text/csv',
                ])->deleteFileAfterSend();
    }

    public function exportcounttrans01($id)
    {
        $inventoryid = $id;
        return (new ExportInventoryCountTrans01($inventoryid))
            ->download('Primer conteo transitorias.csv',
                Excel::CSV, ['Content-Type' => 'text/csv',
                ])->deleteFileAfterSend();
    }

    public function exportcounttrans02($id)
    {
        $inventoryid = $id;
        return (new ExportInventoryCountTrans02($inventoryid))
            ->download('Segundo conteo transitorias.csv',
                Excel::CSV, ['Content-Type' => 'text/csv',
                ])->deleteFileAfterSend();
    }


    public function exportinventorycount($id)
    {
        $inventoryid = $id;
        return (new ExportaInventoryCount($inventoryid))
            ->download('Conteos.csv',
                Excel::CSV, ['Content-Type' => 'text/csv',
                ])->deleteFileAfterSend();
    }

}
