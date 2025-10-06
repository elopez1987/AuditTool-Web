<?php

namespace App\Http\Controllers\Maintanance;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Maintenance\CreateStorageLiftTruckRequest;
use App\Models\Maintenance\CheckListTruckErrorLog;
use App\Models\Maintenance\StorageLiftTruck;
use App\Traits\Web\Maintenance\QueryStorageLiftTrucks;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StorageLiftTruckController extends BaseController
{
    use QueryStorageLiftTrucks;

    public function index()
    {
        $this->authorize('storagelifttrucks.index');
        $status = $this->getStatusForSchema('STM');

        return view('Maintenance.StorageLiftTrucks.index', compact('status'));
    }

    public function getStorageLiftTrucks(Request $request)
    {
        $data = $this->getInformationLiftTrucks($request);

        return DataTables::of($data)
            ->addColumn('btn', 'Maintenance.StorageLiftTrucks.partials.buttons')
            ->rawColumns(['btn', 'statusname'])->toJson();
    }

    public function create()
    {
        $this->authorize('storagelifttrucks.create');
        $typesLiftTrucks = $this->getStatusForSchema('TPM');
        $modelLiftTrucks = $this->getStatusForSchema('MDM');
        $brandLiftTrucks = $this->getStatusForSchema('MCM');

        return view(
            'Maintenance.StorageLiftTrucks.create',
            compact('typesLiftTrucks', 'modelLiftTrucks', 'brandLiftTrucks')
        );
    }

    public function store(CreateStorageLiftTruckRequest $request)
    {
        $centerid = $this->getCenterForUser();
        $ligtTruck = new StorageLiftTruck($request->all() + ['centerid' => $centerid]);
        $ligtTruck->save();

        return $this->redirectBackSuccessMessage('Registro agregado exitosamente.');
    }

    public function show($id)
    {
        $errorLogs = CheckListTruckErrorLog::where('serialnumber', '=', $id)->get();

        return view('Maintenance.StorageLiftTrucks.show', compact('errorLogs'));
    }

    public function edit($id)
    {
        $this->authorize('storagelifttrucks.edit');
        $liftTruck = StorageLiftTruck::findOrFail($id);
        $typesLiftTrucks = $this->getStatusForSchema('TPM');
        $modelLiftTrucks = $this->getStatusForSchema('MDM');
        $brandLiftTrucks = $this->getStatusForSchema('MCM');
        $status = $this->getStatusForSchema('STM');

        return view(
            'Maintenance.StorageLiftTrucks.edit',
            compact(
                'liftTruck',
                'typesLiftTrucks',
                'modelLiftTrucks',
                'brandLiftTrucks',
                'status'
            )
        );
    }

    public function update($id, Request $request)
    {
        $liftTruck = StorageLiftTruck::findOrFail($id);
        $liftTruck->update($request->all());

        return $this->redirectBackSuccessMessage('Registro actualizado con exito.');
    }

    public function destroy($id)
    {
        $this->authorize('storagelifttrucks.destroy');
        $truck = StorageLiftTruck::where('serialnumber', $id)->first();
        $truck->delete();

        return $this->redirectBackSuccessMessage('Registro eliminado exitosamente.');
    }
}
