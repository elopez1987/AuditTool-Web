<?php

namespace App\Http\Controllers\Configuration\TablesDataBase;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\TablesDataBase\SystemTable;
use DB;
use Illuminate\Http\Request;
use Redirect;

class DataBaseController extends BaseController
{
    public function index()
    {
        $this->authorize('updatedatabase');
        $tables = SystemTable::select(['tableid', 'tableid'])->get();
        $columns = [];
        return view('Configuration.MtnDataBase.index', compact('tables', 'columns'));
    }

    public function store(Request $request)
    {
        $error = null;
        DB::beginTransaction();
        try {
            $event = $request['event'];
            if ($event === 'update') {
                DB::table($request['table'])
                    ->where($request['key'], '=', $request['keyValue'])
                    ->update([$request['column'] => $request['newValue']]);
            } elseif ($event === 'delete') {
                DB::table($request['table'])
                    ->where($request['key'], '=', $request['keyValue'])
                    ->delete();
            }
            DB::commit();
            $success = true;
        } catch (\Exception $e) {
            $success = false;
            $error = $e->getMessage();
            DB::rollback();
        }
        if ($success) {
            return redirect::back()->with(session()->flash('success', 'Movimiento ejecutado con exito!!'));
        }
        return redirect::back()->with(session()->flash('error', 'Movimiento no ejecutado:!!' . $error));
    }
}
