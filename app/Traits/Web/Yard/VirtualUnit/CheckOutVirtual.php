<?php

namespace App\Traits\Web\Yard\VirtualUnit;

use App\Models\Yard\ReceiptUnit;
use Auth;
use Carbon\Carbon;

trait CheckOutVirtual
{
    public function checkOutVirtual($model)
    {
        $receiptunit = ReceiptUnit::controlAccessUser()
            ->where('id', $model->folionumber)->first();
        if (!empty($receiptunit)) {
            $receiptunit->containerseal = '0000';
            $receiptunit->shipmentid = $model->shipmentid;
            $receiptunit->outputdate = Carbon::now();
            $receiptunit->updateuser = Auth::user()->id;
            $receiptunit->statusid = 'TR';
            $receiptunit->save();
        }
    }
}
