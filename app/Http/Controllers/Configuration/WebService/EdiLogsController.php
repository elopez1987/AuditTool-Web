<?php

namespace App\Http\Controllers\Configuration\WebService;

use App\Http\Controllers\Controller;
use App\Models\Configuration\EDI\EdiLog;
use Yajra\DataTables\Facades\DataTables;

class EdiLogsController extends Controller
{
    public function index()
    {
        $this->authorize('edilogs.index');
        return view('Configuration.EdiLogs.LogGeneral.index');
    }

    public function getdatalogs()
    {
        $data = EdiLog::orderBy('id', 'desc')->take(250)->get();
        return DataTables::of($data)->toJson();
    }
}
