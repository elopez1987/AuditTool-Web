<?php

namespace App\Http\Controllers\Inventory\MigrationSap;

use App\Http\Controllers\BaseController;
use App\Models\Inventory\MigrationWms\MigrationWms;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MigrationSapController extends BaseController
{
    public function index()
    {
        return view('Inventory.MigrationSap.index');
    }

    public function getMigrationSap(Request $request)
    {
        $data = MigrationWms::with(['center:centerid,name,description',
            'warehouse:warehouseid,name', 'createduser:id,name'])
            ->when($request->input('lpnid'), function ($query) use ($request) {
                $query->where('migration_wms.locationid', '=', $request->input('locationid'));
            })
            ->when($request->input('lpnizel'), function ($query) use ($request) {
                $query->where('migration_wms.lpnizel', '=', $request->input('lpnizel'));
            })
            ->when($request->input('lpnsap'), function ($query) use ($request) {
                $query->where('migration_wms.lpnsap', '=', $request->input('lpnsap'));
            })
            ->select('migration_wms.*');

        return DataTables::of($data)
            ->addColumn('btn', 'Inventory.MigrationSap.buttons')
            ->rawColumns(['btn'])
            ->toJson();
    }

    public function show($id)
    {
        $data = MigrationWms::findOrFail($id);

        return view('Inventory.MigrationSap.show', compact('data'));
    }
}
