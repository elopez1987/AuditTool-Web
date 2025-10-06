<?php

namespace App\Traits\Web\Shipments;

use App\Models\Shipments\StorageIncidenceJourneyDetails;
use App\Models\Shipments\StorageScheduleInverse;
use App\Models\Shipments\StorageScheduleInverseOperationDetail;
use DB;

trait QueryInverseOperation
{
    /**
     * @return array
     */
    public function getDataOperation(StorageScheduleInverse $storagescheduleinverse)
    {
        return StorageIncidenceJourneyDetails::controlAccessUser()
            ->select([
                'storageincidencejourneydetails.folioid',
                'orderdeliveries.shipmentid',
                'storageincidencejourneydetails.delivery',
                'storageincidencejourneydetails.partnumberid',
                'inventorypartnumbers.lngdsc as description',
                'storageincidencejourneydetails.quantity as reportada',
                'storageincidencejourneydetails.unitmeasure',
                DB::raw('SUM(storagescheduleinverseoperationdetails.quantity) as verificada'),
                'storagescheduleinverseoperationdetails.unitmeasureid',
                'systemstatus.name as category', 'storagescheduleinverseoperationdetails.value02 as comment', 'storagescheduleinverseoperationdetails.value01 as location',
            ])
            ->join('orderdeliveries', 'storageincidencejourneydetails.orderid', '=', 'orderdeliveries.id')
            ->join('inventorypartnumbers', function ($join) {
                $join->on('storageincidencejourneydetails.partnumberid', '=', 'inventorypartnumbers.prtnum')
                    ->on('storageincidencejourneydetails.centerid', '=', 'inventorypartnumbers.centerid');
            })
            ->leftJoin('storagescheduleinverseoperationdetails', function ($join) {
                $join->on('storageincidencejourneydetails.orderid', '=', 'storagescheduleinverseoperationdetails.orderid')
                    ->on('storageincidencejourneydetails.delivery', '=', 'storagescheduleinverseoperationdetails.delivery')
                    ->on('storageincidencejourneydetails.partnumberid', '=', 'storagescheduleinverseoperationdetails.partnumberid');
            })
            ->join('systemstatus', function ($join) {
                $join->on('storagescheduleinverseoperationdetails.statusid', '=', 'systemstatus.statusid')
                    ->on('storagescheduleinverseoperationdetails.schemaid', '=', 'systemstatus.schemaid');
            })
            ->where('storageincidencejourneydetails.orderid', '=', $storagescheduleinverse->orderid)
            ->groupBy(
                'storageincidencejourneydetails.folioid',
                'orderdeliveries.shipmentid',
                'storageincidencejourneydetails.delivery',
                'storageincidencejourneydetails.partnumberid',
                'inventorypartnumbers.lngdsc',
                'storageincidencejourneydetails.quantity',
                'storageincidencejourneydetails.unitmeasure',
                'storagescheduleinverseoperationdetails.unitmeasureid',
                'systemstatus.name',
                'storagescheduleinverseoperationdetails.value02',
                'storagescheduleinverseoperationdetails.value01'
            )->get();
    }

    public function getVerificados($orderid)
    {
        return StorageScheduleInverseOperationDetail::controlAccessUser()
            ->select([
                'orderdeliveries.shipmentid', 'storagescheduleinverseoperationdetails.delivery',
                'storagescheduleinverseoperationdetails.partnumberid', 'inventorypartnumbers.lngdsc as description',
                DB::raw('SUM(storagescheduleinverseoperationdetails.quantity) as total'),
            ])
            ->join('orderdeliveries', 'storagescheduleinverseoperationdetails.orderid', '=', 'orderdeliveries.id')
            ->join('inventorypartnumbers', function ($join) {
                $join->on('storagescheduleinverseoperationdetails.partnumberid', '=', 'inventorypartnumbers.prtnum')
                    ->on('storagescheduleinverseoperationdetails.centerid', '=', 'inventorypartnumbers.centerid');
            })
            ->where('storagescheduleinverseoperationdetails.orderid', '=', $orderid)
            ->whereNotIn('storagescheduleinverseoperationdetails.partnumberid', function ($query) use ($orderid) {
                $query->select('partnumberid')
                    ->from('storageincidencejourneydetails')
                    ->where('orderid', $orderid);
            })
            ->groupBy(
                'orderdeliveries.shipmentid',
                'storagescheduleinverseoperationdetails.delivery',
                'storagescheduleinverseoperationdetails.partnumberid',
                'inventorypartnumbers.lngdsc'
            )
            ->get();
    }
}
