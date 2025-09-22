<?php

namespace App\Http\Controllers\Maintanance;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Maintenance\CreateCheckListLiftTrucksRequest;
use App\Models\Maintenance\StorageCheckListLiftTrucks;
use App\Traits\Web\Maintenance\QueryStorageCheckLists;
use Illuminate\Http\Request;

class StorageCheckListLiftTruckController extends BaseController
{
    use QueryStorageCheckLists;

    public function index()
    {
        $this->authorize('storagechecklistlifttrucks.index');
        return view('Maintenance.StorageCheckLists.index');
    }

    public function getDataCheckListLiftTrucks(Request $request)
    {
        $data = $this->getAllCheckList($request);
        return $this->showDataToView(
            $data,
            'Maintenance.StorageCheckLists.partials.buttons'
        );
    }

    public function create()
    {
        $this->authorize('storagechecklistlifttrucks.create');
        $types = $this->getStatusForSchema('TPM');
        return view('Maintenance.StorageCheckLists.create', compact('types'));
    }

    public function store(CreateCheckListLiftTrucksRequest $request)
    {
        $centerid = $this->getCenterForUser();
        $checkList = new StorageCheckListLiftTrucks($request->all() + ['centerid' => $centerid]);
        $checkList->save();
        return $this->redirectBackSuccessMessage('Registro guardado con exito.');
    }

    public function edit($id)
    {
        $this->authorize('storagechecklistlifttrucks.edit');
        $checkList = StorageCheckListLiftTrucks::findOrFail($id);
        $status = $this->getStatusForSchema('STS');
        return view(
            'Maintenance.StorageCheckLists.edit',
            compact('status', 'checkList')
        );
    }

    public function update(Request $request, $id)
    {
        $checkList = StorageCheckListLiftTrucks::findOrFail($id);
        $checkList->update($request->all());
        return $this->redirectBackSuccessMessage('Registro actualizado con exito.');
    }

    public function destroy($id)
    {
        $this->authorize('storagechecklistlifttrucks.destroy');
        $checkList = StorageCheckListLiftTrucks::findOrFail($id);
        $checkList->delete();
        return $this->redirectBackSuccessMessage('Registro eliminado con exito.');
    }
}
