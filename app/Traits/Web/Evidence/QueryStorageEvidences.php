<?php

namespace App\Traits\Web\Evidence;

use App\Models\Evidence\StorageEvidenceProcess;
use App\Models\General\EvidencePhoto;
use App\Models\Yard\StorageLocation;
use DB;

trait QueryStorageEvidences
{
    public function getAllEvidencesShipments($request)
    {
        return StorageEvidenceProcess::controlAccessUser()
            ->leftJoin('systemcenterdistributions', 'storageevidenceoperations.centerid', '=', 'systemcenterdistributions.centerid')
            ->leftJoin('systemwarehouses', 'storageevidenceoperations.warehouseid', '=', 'systemwarehouses.warehouseid')
            ->leftJoin('storageturns', 'storageevidenceoperations.turnid', '=', 'storageturns.id')
            ->leftJoin('receiptproviders', 'storageevidenceoperations.providerid', '=', 'receiptproviders.id')
            ->leftJoin('ordercustomers', 'storageevidenceoperations.customerid', '=', 'ordercustomers.id')
            ->leftJoin('shipmentcarriers', 'storageevidenceoperations.carrierid', 'shipmentcarriers.id')
            ->leftJoin('storagelocations', 'storageevidenceoperations.locationid', '=', 'storagelocations.id')
            ->leftJoin('storagereasons', 'storageevidenceoperations.razonstatusid', 'storagereasons.id')
            ->leftJoin('users', 'storageevidenceoperations.createuser', '=', 'users.username')
            ->leftJoin('systemstatus', 'storageevidenceoperations.statusid', '=', 'systemstatus.statusid')
            ->leftJoin('inventorystockcategories', 'storageevidenceoperations.stockcategoryid', '=', 'inventorystockcategories.id')
            ->ForStatus($request->get('statusid'))
            ->ForRangeDate($request['from_date'], $request['to_date'])
            ->ForShipment($request['shipmentid'])
            ->ForCustomer($request['customerid'])
            ->ForTurn($request['turnid'])
            ->ForCategory($request['categoryid'])
            ->select([
                'storageevidenceoperations.recordid',
                'storageevidenceoperations.statusid',
                'storageevidenceoperations.shipmentid',
                'storageevidenceoperations.workday',
                'storageevidenceoperations.appointment',
                'storageevidenceoperations.econumber',
                'storageevidenceoperations.boxnumber',
                'storageevidenceoperations.created_at as created',
                'systemcenterdistributions.name as center',
                'systemwarehouses.name as warehouse',
                'storageturns.name as turn',
                'receiptproviders.name as provider',
                'ordercustomers.name as customer',
                'shipmentcarriers.name as carrier',
                'storagelocations.name as location',
                'storagereasons.name as razon',
                'users.name as user',
                'systemstatus.name as status',
                'inventorystockcategories.description as origen',
            ]);
    }

    public function getLocationActiveIN()
    {
        return StorageLocation::controlAccessUser()
            ->join('storagezones', 'storagelocations.zoneid', '=', 'storagezones.id')
            ->whereNotIn('storagelocations.name', ['DUMMY'])
            ->whereIn('storagelocations.statusid', ['E'])
            ->where('storagezones.statusid', '=', 'E')
            ->where('storagezones.zoneid', '=', 'CO')
            ->select('storagelocations.name', 'storagelocations.id')->get();
    }

    public function getPhotosForEvidences($model)
    {
        return EvidencePhoto::where('model_id', $model->recordid)
            ->where('model_type', 'StorageEvidenceProcess')
            ->where('auxiliary01', 'PR')->get();
    }

    public function getVerificados($shipmentid)
    {
        return DB::select('
        select shipmentid,partnumberid,sum(quantity) as totalcheck
                from storageevidencecontainers where shipmentid=?
                group by shipmentid, partnumberid
        ', [$shipmentid]);
    }

    public function getEvidencePhotosForShipment($shipmentid)
    {
        return EvidencePhoto::where('model_id', '=', $shipmentid)
            ->where('model_type', '=', 'StorageEvidenceProcess')
            ->where('auxiliary01', '=', 'SH')->get();
    }

    public function getShipmentForDashboard($request)
    {
        return StorageEvidenceProcess::controlAccessUser()
            ->select(['storageevidenceoperations.recordid', 'storageevidenceoperations.shipmentid', 'storageevidenceoperations.workday', 'ordercustomers.name as customer',
                DB::raw('SUM(storageevidencestock.quantity) as solicitado'),
                DB::raw('(SELECT SUM(quantity) FROM storageevidencecontainers WHERE shipmentid = storageevidenceoperations.recordid) as verificado'),
                DB::raw('(SELECT SUM(quantity) FROM storageevidencecontainers WHERE shipmentid = storageevidenceoperations.recordid AND scanuserload IS NOT NULL) as cargado'),
            ])
            ->leftJoin('ordercustomers', function ($join) {
                $join->on('storageevidenceoperations.customerid', '=', 'ordercustomers.id');
                $join->on('storageevidenceoperations.centerid', '=', 'ordercustomers.centerid');
            })
            ->leftJoin('storageevidencestock', function ($join) {
                $join->on('storageevidenceoperations.recordid', '=', 'storageevidencestock.shipmentid');
                $join->on('storageevidenceoperations.centerid', '=', 'storageevidencestock.centerid');
            })
            ->where('storageevidenceoperations.statusid', 'E')
            ->ForShipment($request->get('shipmentid'))
            ->ForRangeDate($request['from_date'], $request['to_date'])
            ->ForTurn($request->get('turnid'))
            ->groupBy(
                'storageevidenceoperations.recordid',
                'storageevidenceoperations.shipmentid',
                'storageevidenceoperations.workday',
                'ordercustomers.name'
            )
            ->get()
            ->toArray();
    }

    public function getTotalVerificado($id)
    {
        return StorageEvidenceProcess::query()
            ->select([
                'storageevidenceoperations.recordid',
                'storageevidenceoperations.shipmentid',
                'storageevidenceoperations.workday',
                'ordercustomers.name as customer',
                'inventorypartnumbers.prtnum as partnumber',
                'inventorypartnumbers.lngdsc',
                DB::raw('SUM(storageevidencestock.quantity) as solicitado'),
                DB::raw('(SELECT SUM(quantity) FROM storageevidencecontainers
                WHERE shipmentid = storageevidenceoperations.recordid AND partnumberid = storageevidencestock.partnumberid) as verificado'),
                DB::raw('(SELECT SUM(quantity) FROM storageevidencecontainers
                WHERE shipmentid = storageevidenceoperations.recordid AND scanuserload IS NOT NULL
                AND partnumberid = storageevidencestock.partnumberid) as cargado'),
            ])
            ->leftJoin('ordercustomers', function ($join) {
                $join->on('storageevidenceoperations.customerid', '=', 'ordercustomers.id')
                    ->on('storageevidenceoperations.centerid', '=', 'ordercustomers.centerid');
            })
            ->leftJoin('storageevidencestock', function ($join) {
                $join->on('storageevidenceoperations.recordid', '=', 'storageevidencestock.shipmentid')
                    ->on('storageevidenceoperations.centerid', '=', 'storageevidencestock.centerid');
            })
            ->leftJoin('inventorypartnumbers', function ($join) {
                $join->on('storageevidencestock.partnumberid', '=', 'inventorypartnumbers.id')
                    ->on('storageevidencestock.centerid', '=', 'inventorypartnumbers.centerid');
            })
            ->where('storageevidenceoperations.recordid', $id)
            ->groupBy(
                'storageevidenceoperations.recordid',
                'storageevidenceoperations.shipmentid',
                'storageevidenceoperations.workday',
                'ordercustomers.name',
                'inventorypartnumbers.prtnum',
                'inventorypartnumbers.lngdsc',
                'storageevidencestock.partnumberid'
            )
            ->get();
    }
}
