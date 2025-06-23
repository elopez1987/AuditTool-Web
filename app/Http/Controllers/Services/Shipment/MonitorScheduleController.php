<?php

namespace App\Http\Controllers\Services\Shipment;

use App\Http\Controllers\Controller;
use App\Models\Shipments\OrderDeliverie;
use App\Traits\ResponseApi;
use DB;

class MonitorScheduleController extends Controller
{
    use ResponseApi;

    public function getStatusShipments()
    {
        $shipments = OrderDeliverie::select([
            'orderdeliveries.shipmentid',
            'ordercustomers.name as cliente',
            'ordercustomers.city as destino',
            DB::raw('ordermonitor.outputdate as maximasalida'),
            'systemstatus.name as estatusembarque',
            'orderdeliveries.folionumber',
        ])
            ->leftJoin('ordercustomers', function ($join) {
                $join->on('orderdeliveries.customerid', '=', 'ordercustomers.id')
                    ->on('orderdeliveries.centerid', '=', 'ordercustomers.centerid')
                    ->on('orderdeliveries.warehouseid', '=', 'ordercustomers.warehouseid');
            })
            ->leftJoin('ordermonitor', function ($join) {
                $join->on('orderdeliveries.id', '=', 'ordermonitor.orderid')
                    ->on('orderdeliveries.centerid', '=', 'ordermonitor.centerid')
                    ->on('orderdeliveries.warehouseid', '=', 'ordermonitor.warehouseid');
            })
            ->leftJoin('systemstatus', 'orderdeliveries.statusid', '=', 'systemstatus.statusid')
            ->whereNotIn('orderdeliveries.statusid', ['DH', 'CA'])
            ->where('orderdeliveries.centerid', 'HENKELTOL01')
            ->get();

        $total = OrderDeliverie::controlAccessUser()->whereNotIn('statusid', ['DH', 'CA'])->count();
        $forstatus = OrderDeliverie::controlAccessUser()
            ->leftJoin('systemstatus', 'orderdeliveries.statusid', '=', 'systemstatus.statusid')
            ->whereNotIn('orderdeliveries.statusid', ['DH', 'CA'])
            ->select(['systemstatus.name as status', 'orderdeliveries.statusid', DB::raw('COUNT(orderdeliveries.statusid) as total')])
            ->groupBy('systemstatus.name', 'orderdeliveries.statusid')->orderByDesc('total')->get();

        return $this->getResponseSuccess('getStatusShipments', [
            'totalshipments' => $total,
            'shipments' => $shipments,
            'forstatus' => $forstatus
        ]);
    }


}
