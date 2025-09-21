<?php

namespace App\Traits\Web\Shipments\OrderMonitor;

use App\Models\Shipments\OrderMonitor;
use App\Models\Yard\Customer\OrderCustomer;
use Carbon\Carbon;

trait UpdateMaximunOutPutShipment
{
    public function updateMaximunOutPutShipment($orderdelivery)
    {
        $customer = OrderCustomer::whereId($orderdelivery->customerid)->select(['timetransfer', 'id'])->first();
        $time = $customer->timetransfer;
        $outputdatemax = Carbon::parse($orderdelivery->downloaddate)->subMinutes($time)->toDateTimeString();
        OrderMonitor::where('centerid', $orderdelivery->centerid)
            ->where('orderid', $orderdelivery->id)
            ->update(['outputdate' => $outputdatemax]);
    }
}
