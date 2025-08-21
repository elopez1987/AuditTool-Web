<?php

namespace App\Traits\Web\Yard\Dashboards;

use App\Models\Yard\ReceiptUnit;
use DB;

trait QuerysDashboard
{


    public function getStadia($request)
    {
        return ReceiptUnit::controlAccessUser()
            ->selectRaw("
                    CAST(AVG(DATEDIFF(MINUTE, entrydate, COALESCE(outputdate, GETDATE()))) / 60 AS VARCHAR) + ' horas ' +
                    CAST(AVG(DATEDIFF(MINUTE, entrydate, COALESCE(outputdate, GETDATE()))) % 60 AS VARCHAR) + ' minutos' AS estadia
                ")
            ->FilterForRangeDate($request['startDate'], $request['endDate'])
            ->get();
    }

    public function getTotalForZoneFilter($request)
    {
        return ReceiptUnit::controlAccessUser()
            ->leftJoin('storagezones as sz', function ($join) {
                $join->on('receiptunits.zoneid', '=', 'sz.id')
                    ->on('receiptunits.centerid', '=', 'sz.centerid')
                    ->on('receiptunits.warehouseid', '=', 'sz.warehouseid');
            })
            ->whereNotIn('receiptunits.statusid', ['TR'])
            ->select('sz.name', DB::raw('COUNT(receiptunits.zoneid) as total'))
            ->groupBy('sz.name')
            ->get();
    }

    public function ForTotalDays($request)
    {
        return ReceiptUnit::controlAccessUser()
            ->select(DB::raw("CONVERT(VARCHAR, receiptunits.entrydate, 3) as fecha, COUNT(CONVERT(VARCHAR, receiptunits.entrydate, 3)) as total"))
            ->FilterForRangeDate($request['startDate'], $request['endDate'])
            ->CarrierArray($request['carrierid'])
            ->Status($request['statusid'])
            ->RazonCode($request->get('razon'))
            ->Warehouse($request['warehouseid'])
            ->groupBy(DB::raw("CONVERT(VARCHAR, receiptunits.entrydate, 3)"))
            ->orderBy(DB::raw("CONVERT(VARCHAR, receiptunits.entrydate, 3)"))
            ->get();
    }

    public function ForTotalEvents($request)
    {
        return ReceiptUnit::controlAccessUser()
            ->leftJoin('storagereasons as sr', 'receiptunits.razonstatusid', '=', 'sr.id')
            ->FilterForRangeDate($request['startDate'], $request['endDate'])
            ->CarrierArray($request['carrierid'])
            ->Status($request['statusid'])
            ->Warehouse($request['warehouseid'])
            ->RazonCode($request->get('razon'))
            ->get()->count();
    }

    public function ForTotalTypeTruck($request)
    {
        return ReceiptUnit::controlAccessUser()
            ->leftJoin('shipmenttrucks as sr', 'receiptunits.truckid', '=', 'sr.id')
            ->select(['sr.name', DB::raw('COUNT(receiptunits.truckid) as total')])
            ->FilterForRangeDate($request['startDate'], $request['endDate'])
            ->Status($request['statusid'])
            ->CarrierArray($request['carrierid'])
            ->RazonCode($request->get('razon'))
            ->Warehouse($request['warehouseid'])
            ->groupBy('sr.name')->get();
    }

    public function ForTotalRason($request)
    {
        return ReceiptUnit::controlAccessUser()
            ->leftJoin('storagereasons as sr', 'receiptunits.razonstatusid', '=', 'sr.id')
            ->select(['sr.name', DB::raw('COUNT(receiptunits.razonstatusid) as total')])
            ->FilterForRangeDate($request['startDate'], $request['endDate'])
            ->CarrierArray($request['carrierid'])
            ->Status($request['statusid'])
            ->RazonCode($request->get('razon'))
            ->Warehouse($request['warehouseid'])
            ->groupBy('sr.name')->get();
    }

    public function ForTotalCarrier($request)
    {
        return ReceiptUnit::controlAccessUser()
            ->leftJoin('shipmentcarriers as sr', 'receiptunits.carrierid', '=', 'sr.id')
            ->select(['sr.name', DB::raw('COUNT(receiptunits.carrierid) as total')])
            ->FilterForRangeDate($request['startDate'], $request['endDate'])
            ->CarrierArray($request['carrierid'])
            ->Status($request['statusid'])
            ->RazonCode($request->get('razon'))
            ->Warehouse($request['warehouseid'])
            ->groupBy('sr.name')->get();
    }

    public function ForTotalStatus($request)
    {
        return ReceiptUnit::controlAccessUser()
            ->leftJoin('systemstatus as sr', 'receiptunits.statusid', '=', 'sr.statusid')
            ->select(['sr.name', 'receiptunits.statusid', DB::raw('COUNT(receiptunits.statusid) as total')])
            ->FilterForRangeDate($request['startDate'], $request['endDate'])
            ->CarrierArray($request['carrierid'])
            ->Status($request['statusid'])
            ->RazonCode($request->get('razon'))
            ->Warehouse($request['warehouseid'])
            ->groupBy('sr.name', 'receiptunits.statusid')
            ->get();
    }
}
