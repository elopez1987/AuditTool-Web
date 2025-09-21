<?php

namespace App\Http\Controllers\Yard\ReceiptTruck;

use App\Http\Controllers\BaseController;
use App\Models\Yard\ReceiptTruck;
use App\Models\Yard\ReceiptUnit;

class ClearTruckStockController extends BaseController
{
    public function cleartruckstock()
    {
        // IDs excluidos de la consulta anidada
        $excludedBarcodes = ReceiptUnit::controlAccessUser()
            ->whereNotIn('statusid', ['TR'])->pluck('foliotruck');

        // Consulta principal
        $receiptTrucks = ReceiptTruck::controlAccessUser()->whereNotIn('id', $excludedBarcodes)
            ->whereNotIn('statusid', ['TR'])
            ->select('centerid', 'warehouseid', 'id', 'statusid')
            ->get();

        // Imprimir resultados
        foreach ($receiptTrucks as $truck) {
            $truck->statusid = 'TR';
            $truck->save();
        }
        return $this->redirectBackSuccessMessage('Tractos limpiados con exito.');
    }
}
