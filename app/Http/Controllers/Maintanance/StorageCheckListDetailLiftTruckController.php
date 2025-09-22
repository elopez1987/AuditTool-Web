<?php

namespace App\Http\Controllers\Maintanance;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Maintenance\CreateCheckListLiftTrucksRequest;
use App\Models\Maintenance\StorageCheckListDetailLiftTruck;
use App\Models\Maintenance\StorageCheckListLiftTrucks;
use Illuminate\Http\Request;

class StorageCheckListDetailLiftTruckController extends BaseController
{
    public function index($id)
    {
        $this->authorize('addchecklistdetailslifttruck');
        $checkList = StorageCheckListLiftTrucks::findOrFail($id);
        $types = $this->getStatusForSchema('TPV');
        $details = StorageCheckListDetailLiftTruck::where('checklistid', '=', $id)->get();
        return view(
            'Maintenance.StorageCheckListDetails.create',
            compact('checkList', 'types', 'details')
        );
    }

    public function store(CreateCheckListLiftTrucksRequest $request, $id)
    {
        $checkList = StorageCheckListLiftTrucks::findOrFail($id);
        StorageCheckListDetailLiftTruck::create([
            'centerid' => $checkList->centerid,
            'checklistid' => $checkList->id,
            'description' => $request['description'],
            'typeid' => $request['typeid'],
            'auxiliary01' => $request['statuslistid'],
        ]);
        return $this->redirectBackSuccessMessage('Detalle guardado con exito.');
    }

    public function updatestatusdetaillifttruck($id)
    {
        $detail = StorageCheckListDetailLiftTruck::findOrFail($id);
        if ($detail->statusid === 'E') {
            $detail->statusid = 'D';
        } else {
            $detail->statusid = 'E';
        }
        $detail->save();
        return $this->redirectBackSuccessMessage('Registro actualizado con exito.');
    }

    public function updatestatusdetaillifttruckedit($id, Request $request)
    {
        $detail = StorageCheckListDetailLiftTruck::findOrFail($id);
        $detail->typeid = $request['typeid'];
        $detail->auxiliary01 = $request['statuslistid'];
        $detail->save();
        return $this->redirectBackSuccessMessage('Regsitro actualizado con exito.');
    }

    public function deletedetailchecklistlifttrucks($id)
    {
        $this->authorize('deletedetailchecklistlifttrucks');
        $detail = StorageCheckListDetailLiftTruck::findOrFail($id);
        $detail->delete();
        return $this->redirectBackSuccessMessage('Regsitro eliminado con exito.');
    }
}
