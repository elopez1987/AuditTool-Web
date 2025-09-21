<?php

namespace App\Http\Controllers\Yard\ReceiptTruck;

use App\Http\Controllers\BaseController;
use App\Models\Yard\ReceiptTruck;
use Illuminate\Http\Request;

class ReceiptTruckTraceController extends BaseController
{
    public function index()
    {
        $this->authorize('receipttrucktrace.index');
        return view('Yards.ReceiptTruckTrace.index');
    }

    public function getTruckTrace(Request $request)
    {
        $data = ReceiptTruck::controlAccessUser()
            ->select(['receipttrucks.*', 'shipmentcarriers.name as carriername', 'systemstatus.name as status',
                'systemwarehouses.name as warehouse', 'u1.name as creador', 'u2.name as usersalida',
            ])
            ->Folio($request->get('folionumber'))
            ->Truck($request->get('trucknumber'))
            ->Barcode($request->get('barcode'))
            ->FilterForRangeDate($request->get('startDate'), $request->get('endDate'))
            ->leftJoin('shipmentcarriers', 'receipttrucks.carrierid', 'shipmentcarriers.id')
            ->leftJoin('systemstatus', 'receipttrucks.statusid', 'systemstatus.statusid')
            ->leftJoin('systemwarehouses', 'receipttrucks.warehouseid', 'systemwarehouses.warehouseid')
            ->leftJoin('users as u1', 'receipttrucks.createuser', '=', 'u1.id')
            ->leftJoin('users as u2', 'receipttrucks.updateuser', '=', 'u2.id')
            ->get();
        return $this->showDataToView($data, '');
    }
}
