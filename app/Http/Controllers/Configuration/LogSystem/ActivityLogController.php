<?php

namespace App\Http\Controllers\Configuration\LogSystem;

use App\Http\Controllers\Controller;
use App\Models\Configuration\ActivityLog\ActivityLog;
use App\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ActivityLogController extends Controller
{

    public function index()
    {
        $this->authorize('activitylog.index');
        $descriptions = ActivityLog::select(['description', 'description'])->distinct()->get();
        $models = ActivityLog::select(['subject_type', 'subject_type'])->distinct()->get();
        $users = User::select(['name', 'id'])->where('statusid','=','E')->get();
        return view('Configuration.ActivityLogs.index', compact('descriptions', 'models', 'users'));
    }

    public function getDataActivityLog(Request $request)
    {
        $data = ActivityLog::with('user')
            ->ForDescription($request->get('description'))
            ->ForModel($request->get('model'))
            ->ForDate($request->get('date_in'), $request->get('date_out'))
            ->ForSubject($request->get('subject_id'))
            ->ForUser($request->get('user'))
            ->ForFolio($request->get('id'))->limit(1000)
            ->get();
        return DataTables::of($data)
            ->addColumn('btn', 'Configuration.ActivityLogs.partials.buttons')
            ->rawColumns(['btn'])
            ->toJson();
    }

    public function show($id)
    {
        $register = ActivityLog::findOrFail($id);
        $jsonObject = json_decode($register->properties);
        return view('Configuration.ActivityLogs.show', compact('register', 'jsonObject'));
    }


}
