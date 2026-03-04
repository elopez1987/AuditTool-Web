<?php

namespace App\Http\Controllers\Evidence;

use App\Http\Controllers\BaseController;
use App\Models\Evidence\StorageContainersTravel;
use App\Models\Evidence\StorageContainerTravelTrace;
use App\Models\Evidence\StorageMasterEvidence;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use PDF;
use Yajra\DataTables\Facades\DataTables;

class ReportAuditShipmentController extends BaseController
{
    public function index()
    {
        $this->authorize('reportauditshipments.index');

        return view('Evidence.ReportAudit.index');
    }

    public function getDetailContainerID(Request $request)
    {
        $data = $this->getDetailContainer($request);

        return DataTables::of($data)
            ->addColumn('img', 'Evidence.ReportAudit.img')
            ->addColumn('btn', 'Evidence.ReportAudit.btn')
            ->rawColumns(['img', 'btn'])
            ->toJson();
    }

    public function getDetailContainer(Request $request)
    {
        return StorageContainersTravel::controlAccessUser()
            ->leftJoin('inventorypartnumbers', function ($join) {
                $join->on('storagecontainerstravel.partnumberid', '=', 'inventorypartnumbers.id')
                    ->on('storagecontainerstravel.centerid', '=', 'inventorypartnumbers.centerid');
            })
            ->leftJoin('users as u1', function ($join) {
                $join->on('storagecontainerstravel.scanuser', '=', 'u1.username');
            })
            ->leftJoin('storageevidences', 'storagecontainerstravel.lpnid', '=', 'storageevidences.modelid')
            ->select([
                'storagecontainerstravel.lpnid',
                'storagecontainerstravel.quantity',
                'storagecontainerstravel.filebox',
                'storagecontainerstravel.value01 as shipmentid',
                'storagecontainerstravel.created_at',
                'u1.name as user',
                'inventorypartnumbers.prtnum as partnumberid',
                'inventorypartnumbers.lngdsc as description',
                'storageevidences.path',
            ])
            ->ForLpn($request->get('lpnid'))
            ->ForShipment($request->get('shipmentid'))
            ->ForSku($request->get('partnumberid'))
            ->get();
    }

    public function show($id)
    {
        $lpn = StorageContainersTravel::where('lpnid', '=', $id)->first();
        $details = StorageContainerTravelTrace::where('lpnid', '=', $id)->get();

        return view('Evidence.ReportAudit.show', compact('lpn', 'details'));
    }

    public function getDocumentPDFKardex($id)
    {

        $model = StorageContainersTravel::where('lpnid', '=', $id)->first();
        $traces = StorageContainerTravelTrace::where('lpnid', '=', $id)->get();
        $fortmat = 'Evidence.ReportAudit.Formats.papeleta';
        $pdf = PDF::loadView($fortmat, compact('traces', 'model'));

        return $pdf->download('KARDEX-' . $id . '.pdf');
    }

    public function getMasterLabel(Request $request)
    {
        $data = StorageMasterEvidence::controlAccessUser()
            ->leftJoin('users as u1', 'storagemasterevidence.createuser', '=', 'u1.username')
            ->leftJoin('users as u2', 'storagemasterevidence.closeuser', '=', 'u2.username')
            ->leftJoin('systemstatus', 'storagemasterevidence.statusid', '=', 'systemstatus.statusid')
            ->select([
                'storagemasterevidence.masterid',
                'storagemasterevidence.createdate',
                'storagemasterevidence.closedate',
                'u1.name as createuser',
                'u2.name as closeuser',
            ])
            ->ForMaster($request->get('masterid'))
            ->get();

        return DataTables::of($data)
            ->addColumn('btn', 'Evidence.ReportAudit.DetailMaster.btn')
            ->rawColumns(['btn'])
            ->toJson();
    }

    public function detailmasteraudit($id)
    {
        $master = StorageMasterEvidence::controlAccessUser()->where('masterid', '=', $id)->first();
        $details = $this->getDetailMaster($id);

        return view('Evidence.ReportAudit.DetailMaster.show', compact('master', 'details'));
    }

    public function detailmasterpdf($id)
    {
        $details = $this->getDetailMaster($id);
        $fortmat = 'Evidence.ReportAudit.Formats.papeletadetailmaster';
        $pdf = PDF::loadView($fortmat, compact('details'));

        return $pdf->download('DETALLE DE MASTER' . '.pdf');
    }

    public function getDetailMaster($masterid): Collection
    {
        return DB::table('storagemasterevidence as a')
            ->select(
                'a.masterid',
                's.lpnid',
                'i.prtnum as partnumberid',
                'i.lngdsc as description',
                'b.quantity',
                'b.created_at'
            )
            ->leftJoin('storagemasterdetailevidence as s', 'a.masterid', '=', 's.masterid')
            ->leftJoin('storagecontainerstravel as b', function ($join) {
                $join->on('s.lpnid', '=', 'b.lpnid')
                    ->on('a.centerid', '=', 'b.centerid');
            })
            ->leftJoin('inventorypartnumbers as i', function ($join) {
                $join->on('b.partnumberid', '=', 'i.id')
                    ->on('b.centerid', '=', 'i.centerid');
            })
            ->where('a.masterid', $masterid)
            ->get();
    }

    public function reportdashboard()
    {
        $this->authorize('reportauditshipments.dashboard');
        $masters = DB::select("
        select a.masterid,a.createdate,a.value01,count(s.lpnid) as totallpns,sum(b.quantity) totalpiezas
                from storagemasterevidence a
                         left join dbo.storagemasterdetailevidence s on a.masterid = s.masterid
                         left join storagecontainerstravel b on s.lpnid = b.lpnid and a.centerid = b.centerid
                where a.statusid='E'
                group by a.masterid, a.createdate, a.value01
                ");

        return view('Evidence.Dashboards.DC.index', compact('masters'));

    }
}
