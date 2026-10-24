<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\TablesDataBase\SystemColumn;
use App\Models\Security\Fuctions\SecurityFunction;
use App\Traits\Web\General\GetParameters;
use DB;

class SelectDynamicController extends Controller
{
    use GetParameters;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getColumnForTable($table)
    {
        $columns = SystemColumn::where('tableid', $table)->select(['columnid'])->get()->toArray();
        array_unshift($columns, ['columnid' => '', 'columnid' => 'Seleccione una opción']);

        return response()->json($columns);
    }

    public function getPrinters()
    {
        $printers = SystemStatus::whereSchemaid('PRT')
            ->select(['statusid', 'name'])->get();

        return response()->json($printers);
    }

    public function getFunctionForModule($moduleid)
    {
        return SecurityFunction::where('moduleid', $moduleid)
            ->select('description', 'functionid')->get();
    }
}
