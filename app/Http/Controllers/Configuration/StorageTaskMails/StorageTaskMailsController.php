<?php

namespace App\Http\Controllers\Configuration\StorageTaskMails;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\Mails\StorageTaskMail;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class StorageTaskMailsController extends BaseController
{
    public function index()
    {
        return view('Configuration.StorageTaskMail.index');
    }

    public function taskmailslist()
    {
        $data = StorageTaskMail::controlAccessUser()
            ->with(['center:centerid,description', 'warehouse:warehouseid,name', 'status:statusid,name'])
            ->get();

        return DataTables::of($data)
            ->addColumn('btn', 'Configuration.StorageTaskMail.partials.buttons')
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)
                    ->setTimezone('America/Mexico_City')
                    ->format('Y-m-d H:i');
            })
            ->rawColumns(['btn'])
            ->toJson();
    }

    public function destroy($id)
    {
        StorageTaskMail::destroy($id);

        return $this->redirectBackSuccessMessage('Registro eliminado con éxito.');
    }
}
