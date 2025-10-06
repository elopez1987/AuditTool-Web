<?php

namespace App\Traits\Web\Shipments;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Carbon\Carbon;
use DB;

trait QueryShipmentMonitor
{
    public function shipmentpending()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();

        return DB::table('orderdeliveries')
            ->select(DB::raw('COUNT(orderdeliveries.id) as total'))
            ->join('shipmentjourneys', function ($join) {
                $join->on('orderdeliveries.id', '=', 'shipmentjourneys.orderid')
                    ->on('orderdeliveries.centerid', '=', 'shipmentjourneys.centerid')
                    ->on('orderdeliveries.warehouseid', '=', 'shipmentjourneys.warehouseid');
            })
            ->whereBetween('orderdeliveries.downloaddate', [Carbon::now()->subDays(15), Carbon::now()->subDay()])
            ->where('shipmentjourneys.statusid', '!=', 'ET')
            ->whereIn('orderdeliveries.centerid', $centers)
            ->whereIn('orderdeliveries.warehouseid', $warehouses)
            ->first();
    }

    public function shipmenttoday()
    {
        return DB::table('orderdeliveries')
            ->select(DB::raw('count(id) as total'))
            ->where('downloaddate', '=', Carbon::now())
            ->first();
    }
}
