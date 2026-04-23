<?php

namespace App\Http\Controllers\Yard\ReceiptTruck;

use App\Http\Controllers\BaseController;
use App\Models\Yard\ReceiptTruck;
use App\Models\Yard\ReceiptUnit;

class ClearTruckStockController extends BaseController
{
    public function cleartruckstock()
    {
        $excludedBarcodes = ReceiptUnit::controlAccessUser()
            ->whereNotIn('statusid', ['TR'])
            ->pluck('foliotruck');

        ReceiptTruck::controlAccessUser()
            ->whereNotIn('id', $excludedBarcodes)
            ->whereNotIn('statusid', ['TR'])
            ->update(['statusid' => 'TR']);

        return $this->redirectBackSuccessMessage('Tractos limpiados con éxito.');
    }
}
