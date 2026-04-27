<?php

namespace App\Http\Controllers\Shipments\Monitors\Traffic;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends BaseController
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $shipments = $this->getdatashipmentforcustomerservices();

            return $this->showDataToView($shipments, '');
        }

        return view('Shipments.OrderMonitor.Customer.index');
    }

    private function getdatashipmentforcustomerservices()
    {
        return DB::table('orderdeliveries as od')
            ->selectRaw('
        CONVERT(varchar(10), od.workday, 103) AS fecha,
        s.name AS turno,
        od.loadingdate,
        om.outputdate AS maxsalida,
        se.name AS status,
        od.shipmentid,
        s2.name AS carriername,
        oc.name AS cliente,
        oc.city,
        od.downloaddate,
        r2.entrydate AS fentrada,
        od.auxiliary01 AS dvnegocio,
        sp.auxiliary02 AS armado,
        sh.totalpieces AS cajas,
        sh.totalcontainers AS pallets,
        sh.totalweight AS peso,
        DATEDIFF(HOUR, GETDATE(), om.outputdate) AS diferencia_horas
    ')
            ->leftJoin('storageturns as s', 'od.turnid', '=', 's.id')
            ->leftJoin('systemstatus as se', 'od.statusid', '=', 'se.statusid')
            ->leftJoin('shipmentcarriers as s2', function ($join) {
                $join->on('od.carrierid', '=', 's2.id')
                    ->on('od.centerid', '=', 's2.centerid');
            })
            ->leftJoin('ordercustomers as oc', function ($join) {
                $join->on('od.customerid', '=', 'oc.id')
                    ->on('od.centerid', '=', 'oc.centerid');
            })
            ->leftJoin('receiptunits as r2', function ($join) {
                $join->on('od.folionumber', '=', 'r2.id')
                    ->on('od.centerid', '=', 'r2.centerid')
                    ->on('od.warehouseid', '=', 'r2.warehouseid');
            })
            ->leftJoin('ordermonitor as om', function ($join) {
                $join->on('od.id', '=', 'om.orderid')
                    ->on('od.centerid', '=', 'om.centerid');
            })
            ->leftJoin('shipmentdataoperation as sp', function ($join) {
                $join->on('od.id', '=', 'sp.orderid')
                    ->on('od.centerid', '=', 'sp.centerid');
            })
            ->leftJoin('shipmentheader as sh', function ($join) {
                $join->on('od.id', '=', 'sh.orderid')
                    ->on('od.centerid', '=', 'sh.centerid');
            })
            ->whereNotIn('od.statusid', ['CA', 'DH']);
    }
}
