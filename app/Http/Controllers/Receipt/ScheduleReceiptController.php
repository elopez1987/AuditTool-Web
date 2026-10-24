<?php

namespace App\Http\Controllers\Receipt;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Yard\StorageReason;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ScheduleReceiptController extends BaseController
{
    public function index()
    {
        $this->authorize('schedulereceipt.index');
        $status = SystemStatus::whereSchemaid('SRE')->whereNotIn('statusid', ['CE', 'IG'])
            ->select('name', 'statusid')->get();
        $providers = $this->getReceiptProviderForSelect();
        $warehouses = $this->getWarehouseForSelect();
        $reasons = StorageReason::select('id', 'name')->whereRaw("charindex('RES-02', configurationflag) > 0")->get();

        return view(
            'Receipt.ScheduleReceipt.index',
            compact('status', 'providers', 'warehouses', 'reasons'),
        );
    }

    public function getDataReceipt(Request $request)
    {
        $data = ReceiptAsn::controlAccessUser()->with(['status', 'provider', 'carrier', 'turn'])
            ->leftjoin('systemwarehouses', 'receiptasn.warehouseid', '=', 'systemwarehouses.warehouseid')
            ->leftJoin('receiptproviders', 'receiptasn.providerid', '=', 'receiptproviders.id')
            ->leftJoin('inventorystockcategories', 'receiptasn.stockcategoryid', '=', 'inventorystockcategories.id')
            ->whereNotIn('receiptasn.statusid', ['CE', 'IG', 'PG'])
            ->select([
                'receiptasn.id', 'receiptasn.workday', 'receiptasn.shipmentid', 'receiptasn.addasndate',
                'receiptasn.billoflading', 'receiptproviders.name as providername',
                'inventorystockcategories.description as type',  'receiptasn.statusid', 'receiptasn.cancellationdate',
                'receiptasn.goodissuedate', 'receiptasn.statusid',
                'systemwarehouses.name as warehouse', 'receiptasn.centerid',
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
