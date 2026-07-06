<?php

namespace App\Http\Controllers\Maintanance;

use App\Http\Controllers\BaseController;
use App\Models\Maintenance\StorageCheckListLiftTrucks;
use App\Models\Maintenance\StorageCheckListTruckEvidences;
use Illuminate\Http\Request;

class StorageChekListTruckEvidenceController extends BaseController
{
    public function index($id)
    {
        $checkList = StorageCheckListLiftTrucks::findOrFail($id);
        $details = StorageCheckListTruckEvidences::where('storagechecklistslifttruck_id', $id)
            ->with('status:statusid,name')
            ->get();
        $status = $this->getStatusSTS();

        return view('Maintenance.StorageCheckListEvidence.index',
            compact('checkList', 'details', 'status'));
    }

    public function store(Request $request, $id)
    {

        StorageCheckListTruckEvidences::create(
            [
                'storagechecklistslifttruck_id' => $id,
                'name' => $request->name,
            ],
        );

        return $this->redirectBackSuccessMessage('Detalle agregado correctamente');

    }

    public function update($id, Request $request)
    {
        $data = StorageCheckListTruckEvidences::findOrFail($id);
        $data->update([
            'name' => $request->name,
            'statusid' => $request->statusid,
        ]);

        return $this->redirectBackSuccessMessage('Detalle actualizado correctamente');
    }
}
