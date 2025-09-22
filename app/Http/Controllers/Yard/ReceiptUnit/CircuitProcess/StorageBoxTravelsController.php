<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\CircuitProcess;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageBoxTravel;
use App\Models\Yard\StorageUnitTravel;
use Auth;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StorageBoxTravelsController extends BaseController
{
    public function index()
    {
        $this->authorize('monitorcircuitplant');
        return view('Yards.MonitorBoxTravels.index');
    }

    public function getDataBoxTravels(Request $request)
    {
        $user = SecurityAccessWarehouse::where('username', Auth::user()->username)->first(['centerid']);
        $center = SystemCenterDistribution::where('centerid', $user->centerid)->first(['auxiliary02']);
        $data = StorageBoxTravel::where('centerid', '=', $center->auxiliary02)
            ->Shipmentid($request->get('shipmentid'))
            ->BoxNumber($request->get('boxnumber'))
            ->Folio($request->get('folionumber'))
            ->Foliowm($request->get('folionumberwm'))
            ->where('statusid', '=', 'PL');
        return DataTables::of($data)
            ->addColumn('btn', 'Yards.MonitorBoxTravels.partials.buttons')
            ->addColumn('add', 'Yards.MonitorBoxTravels.partials.add')
            ->rawColumns(['btn', 'add'])
            ->toJson();
    }

    public function close($id)
    {
        $folio = StorageUnitTravel::where('folioplant', '=', $id)
            ->first(['statusid', 'circuitclose', 'folioplant', 'id']);
        $folio->circuitclose = now();
        $folio->statusid = 'CL';
        $folio->save();
        return $this->redirectBackSuccess();
    }

    public function addfolio($id, Request $request)
    {
        $foliowm = ReceiptUnit::searchForId($request['foliowarehouse']);
        if (empty($foliowm)) {
            return $this->errorResponse('No existe el número de folio, favor de revisar!!');
        }
        $travel = StorageUnitTravel::where('folioplant', $id)->first();
        $foliopl = ReceiptUnit::where('id', $travel->folioplant)->first();
        if ($foliowm->boxnumber === $foliopl->boxnumber) {
            $travel->foliowarehouse = $foliowm->id;
            $travel->checkinwarehouse = $foliowm->entrydate;
            $travel->save();
            return $this->redirectBackSuccessMessage('Folio agregado con exito');
        }
        return $this->errorResponse('El número de placa no  corresponde');
    }
}
