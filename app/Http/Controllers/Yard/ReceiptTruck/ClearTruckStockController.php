<?php

namespace App\Http\Controllers\Yard\ReceiptTruck;

use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\DB;

class ClearTruckStockController extends BaseController
{
    public function cleartruckstock()
    {
        //        $excludedBarcodes = ReceiptUnit::controlAccessUser()
        //            ->whereNotIn('statusid', ['TR'])
        //            ->pluck('foliotruck');
        //
        //        ReceiptTruck::controlAccessUser()
        //            ->whereNotIn('id', $excludedBarcodes)
        //            ->whereNotIn('statusid', ['TR'])
        //            ->update(['statusid' => 'TR']);

        DB::table('receipttrucks')
            ->whereNotIn('id', function ($query) {
                $query->select('foliotruck')
                    ->from('receiptunits')
                    ->whereNotIn('statusid', ['TR'])
                    ->whereNotNull('foliotruck');
            })
            ->update(['statusid' => 'TR']);

        return $this->redirectBackSuccessMessage('Tractos limpiados con éxito.');
    }
}
