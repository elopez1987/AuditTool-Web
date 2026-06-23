<?php

namespace App\Http\Controllers\Shipments\Monitors\Traffic;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\OrderDeliverie;
use DB;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MonitorShipmentController extends BaseController
{
    public function monitortrafico()
    {
        $this->authorize('monitortrafico');

        return view('Shipments.MonitorShipments.MonitorTrafico.index');
    }

    public function getDataMonitorTrafico(Request $request)
    {
        $data = OrderDeliverie::controlAccessUser()
            ->select([
                'orderdeliveries.shipmentid',
                DB::raw('UPPER(b.name) as customer'),
                DB::raw('UPPER(b.city) as city'),
                'c.name as carrier',
                'orderdeliveries.loadingdate',
                DB::raw("
					CASE
						WHEN GETDATE() >= orderdeliveries.loadingdate
							THEN CASE
								WHEN GETDATE() < DATEADD(minute, 30, orderdeliveries.loadingdate)
								THEN 'Validar Transporte'
								ELSE 'Fuera de tiempo'
							END
						ELSE 'En tiempo'
					END as status
				"), ])
            ->leftJoin('ordercustomers as b', function ($join) {
                $join->on('orderdeliveries.customerid', '=', 'b.id')
                    ->on('orderdeliveries.centerid', '=', 'b.centerid');
            })
            ->leftJoin('shipmentcarriers as c', function ($join) {
                $join->on('orderdeliveries.carrierid', '=', 'c.id')
                    ->on('orderdeliveries.centerid', '=', 'c.centerid');
            })
            ->whereNotIn('orderdeliveries.statusid', ['DH', 'CA'])
            ->when($request->input('shipmentid'), function ($q) use ($request) {
                $q->where('shipmentid', $request->input('shipmentid'));
            })
            ->whereNull('orderdeliveries.folionumber');

        return DataTables::of($data)->toJson();
    }
}
