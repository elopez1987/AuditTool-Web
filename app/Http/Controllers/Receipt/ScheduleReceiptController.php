<?php

namespace App\Http\Controllers\Receipt;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Receipt\ReceiptAsn;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ScheduleReceiptController extends BaseController
{
    public function index()
    {
        $this->authorize('schedulereceipt.index');
        $status = SystemStatus::whereSchemaid('SRE')->whereNotIn('statusid', ['CE', 'IG'])
            ->select('name', 'statusid')->get();
        $warehouses = $this->getWarehouseForSelect();

        return view(
            'Receipt.ScheduleReceipt.index',
            compact('status', 'warehouses'),
        );
    }

    public function getDataReceipt(Request $request)
    {
        $data = ReceiptAsn::controlAccessUser()->with(['status', 'provider'])
            ->leftjoin('systemwarehouses', 'receiptasn.warehouseid', '=', 'systemwarehouses.warehouseid')
            ->leftJoin('receiptproviders', 'receiptasn.providerid', '=', 'receiptproviders.id')
            ->whereNotIn('receiptasn.statusid', ['CE', 'IG', 'PG'])
            ->select([
                'receiptasn.id', 'receiptasn.workday', 'receiptasn.shipmentid', 'receiptasn.addasndate',
                'receiptasn.billoflading', 'receiptproviders.name as providername',
                'receiptasn.statusid', 'receiptasn.cancellationdate',
                'receiptasn.goodissuedate', 'receiptasn.statusid',
                'systemwarehouses.name as warehouse', 'receiptasn.centerid', 'receiptasn.uuid_asn',
            ]);

        return DataTables::of($data)
            ->addColumn('btn', 'Receipt.ScheduleReceipt.partials.buttons')
            ->rawColumns(['btn'])->toJson();
    }

    public function clearSchedulle()
    {
        ReceiptAsn::controlAccessUser()
            ->where('statusid', 'TE')->update(['statusid' => 'IG']);

        return $this->redirectBackSuccessMessage('Horario de descarga limpiado correctamente');
    }
}
