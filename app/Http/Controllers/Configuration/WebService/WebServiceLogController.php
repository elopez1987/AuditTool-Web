<?php

namespace App\Http\Controllers\Configuration\WebService;

use App\Exports\Configuration\WebService\ExportLogWebService;
use App\Http\Controllers\BaseController;
use App\Models\Configuration\EDI\SystemEventWebServiceIzel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class WebServiceLogController extends BaseController
{
    public function index()
    {
        $this->authorize('webservicelog');

        return view('Configuration.LogWebService.index');
    }

    public function getDataLogsWs(Request $request)
    {
        $data = SystemEventWebServiceIzel::controlAccessUser()
            ->with(['center:centerid,name,description', 'warehouse:warehouseid,name'])
            ->FolioNumber($request->get('folionumber'))
            ->BoxNumber($request->get('boxnumber'));

        return DataTables::of($data)
            ->addColumn('btn', 'Configuration.LogWebService.partials.buttons')
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d/m/Y H:i:s');
            })
            ->rawColumns(['btn'])->toJson();
    }

    public function show($id)
    {
        $log = SystemEventWebServiceIzel::findOrFail($id);

        return view('Configuration.LogWebService.show', compact('log'));
    }

    public function exportlogwebservice()
    {
        return Excel::download(new ExportLogWebService, 'Reporte de Web Service.xlsx')->deleteFileAfterSend();
    }
}
