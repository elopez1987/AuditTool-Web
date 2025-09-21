<?php

namespace App\Http\Controllers\Shipments\StorageDoor;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\StorageDoorStage;
use App\Models\Yard\StorageLocation;
use App\Traits\Web\Shipments\QueryStorageDoorStages;
use Illuminate\Http\Request;

class StorageDoorStageController extends BaseController
{
    use QueryStorageDoorStages;

    public function index()
    {
        $this->authorize('storagedoorstage.index');
        $stages = $this->getAllStagesForCenter();
        return view('Shipments.StorageDoorStages.index', compact('stages'));
    }

    public function edit($id)
    {
        $this->authorize('storagedoorstage.edit');
        $location = StorageLocation::findOrFail($id);
        $doors = $this->getAllDoorsForStage($id);
        $stagesForDoor = $this->getStagesForDoor($id);
        return view(
            'Shipments.StorageDoorStages.edit',
            compact('location', 'doors', 'stagesForDoor')
        );
    }

    public function update(Request $request, $id)
    {
        $location = StorageLocation::findOrFail($id);
        if ($request['doorid'] !== '') {
            $total = count($request['doorid']) - 1;
            for ($i = 0; $i <= $total; $i++) {
                StorageDoorStage::create([
                    'doorid' => $request['doorid'][$i],
                    'stageid' => $id,
                    'centerid' => $location->centerid,
                ]);
            }
        }
        return $this->redirectBackSuccessMessage('Modulos agregados con exito.');
    }

    public function deletestageofdoor(Request $request)
    {
        StorageDoorStage::destroy($request['id']);
        return $this->responseSuccessAjax('', '');
    }
}
