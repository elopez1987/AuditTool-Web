<?php

namespace App\Http\Controllers\Configuration\TablesDataBase;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\TablesDataBase\SystemColumn;
use App\Models\Configuration\TablesDataBase\SystemTable;
use DB;
use Illuminate\Http\Request;

class SystemTableController extends BaseController
{
    public function index()
    {
        $this->authorize('systemtables.index');

        return view('Configuration.SystemTables.index');
    }

    public function getsystemtables(Request $request)
    {
        $data = SystemTable::ForName($request->get('tableid'));

        return $this->showDataToView($data, 'Configuration.SystemTables.partials.buttons');
    }

    public function edit(SystemTable $systemtable)
    {
        $this->authorize('systemtables.edit');

        return view('Configuration.SystemTables.edit', compact('systemtable'));
    }

    public function update(Request $request, SystemTable $systemtable)
    {
        $systemtable->update($request->all());

        return $this->infoResponse('systemtables.index');
    }

    public function uploadfiletables()
    {
        DB::table('systemtables')->delete();
        $tables = DB::table('INFORMATION_SCHEMA.TABLES')->select('TABLE_NAME')->get();
        foreach ($tables as $table) {
            SystemTable::updateOrCreate([
                'tableid' => $table->TABLE_NAME,
            ]);
        }

        return $this->redirectBackSuccessMessage('Tablas agregadas con exito.');
    }

    public function uploadfilecolumns($id)
    {
        $columns = DB::select("select TABLE_NAME as tableid, COLUMN_NAME as columnid
                from INFORMATION_SCHEMA.COLUMNS
                where TABLE_SCHEMA = 'dbo'
                  and TABLE_NAME = ?
                order by ORDINAL_POSITION;", [$id]);

        foreach ($columns as $column) {
            SystemColumn::updateOrCreate([
                'tableid' => $id,
                'columnid' => $column->columnid,
            ]);
        }

        return $this->redirectBackSuccessMessage('Columnas agregadas correctamente a las tablas.');
    }

    public function addColumnToTable($id)
    {
        $table = SystemTable::findOrFail($id);
        $columns = SystemColumn::where('tableid', $id)->get();

        return view('Configuration.SystemTables.addColumnToTable', compact('table', 'columns'));
    }

    public function updatecolumntotable(CreateColumnTableRequest $request, $id)
    {
        SystemColumn::updateOrCreate(
            [
                'tableid' => $request['tableid'],
                'columnid' => $request['columnid'],
            ],
            [
                'description' => $request['descriptionname'],
                'shortdesc' => $request['shortdesc'],
            ]
        );

        return $this->redirectBackSuccess();
    }
}
