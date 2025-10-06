<?php

namespace App\Http\Controllers\Maintanance;

use App\Http\Controllers\BaseController;
use App\Models\General\EvidencePhoto;
use App\Models\Maintenance\CheckListLiftTrucks;
use App\Models\Maintenance\StorageCheckListActiveTruck;
use App\Traits\Web\Maintenance\QueryCheckListLiftTrucks;
use Illuminate\Http\Request;
use PDF;

class CheckListLiftTruckController extends BaseController
{
    use QueryCheckListLiftTrucks;

    public function index()
    {
        $this->authorize('checklistlifttrucks.index');

        return view('Maintenance.CheckListLiftTrucks.index');
    }

    public function getCheckListTruck(Request $request)
    {
        $data = $this->getAllCheckListTruck($request);

        return $this->showDataToView(
            $data,
            'Maintenance.CheckListLiftTrucks.partials.buttons'
        );
    }

    public function printFormatToCheckList($id)
    {
        $truckChecklist = CheckListLiftTrucks::find($id);
        $details = $this->getDetailCheckListForTruck($truckChecklist, $id);
        $typeIdToFormatMap = [
            'MT-01' => 'Maintenance.CheckListLiftTrucks.Formats.montacargaselectricohomsen',
            'MT-02' => 'Maintenance.CheckListLiftTrucks.Formats.montacargaselectricohompie',
            'MT-03' => 'Maintenance.CheckListLiftTrucks.Formats.patinhidraulico',
            'MT-04' => 'Maintenance.CheckListLiftTrucks.Formats.montacargacombustion',
            'MT-05' => 'Maintenance.CheckListLiftTrucks.Formats.patinelectrico',
        ];
        $typeId = $truckChecklist->truck->typeid;
        if (! array_key_exists($typeId, $typeIdToFormatMap)) {
            return $this->errorResponse('No existe un formato definido para este tipo de check list.');
        }
        $format = $typeIdToFormatMap[$typeId];
        $pdf = PDF::loadView($format, compact('truckChecklist', 'details'));

        return $pdf->stream('Check List de equipo -' . $truckChecklist->id . '.pdf');
    }

    public function show($id)
    {
        $checkList = CheckListLiftTrucks::controlAccessUser()->where('id', $id)->first();
        $errorsCheck = StorageCheckListActiveTruck::controlAccessUser()->where('checklisttruckid', $id)->get();
        $evidences = EvidencePhoto::where('model_type', '=', 'StorageLiftTruck')
            ->where('auxiliary01', '=', 'EVI')->where('model_id', $id)->get();

        return view('Maintenance.CheckListLiftTrucks.show', compact('checkList', 'errorsCheck', 'evidences'));
    }
}
