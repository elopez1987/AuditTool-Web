<?php

namespace App\Http\ControllersApp\Maintanance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Maintanance\Exception;
use App\Http\Requests\Api\Maintanance\SaveCheckListMtnRequest;
use App\Http\Requests\Api\Maintanance\SaveKeySupervisorRequest;
use App\Models\Maintenance\CheckListDetailLiftTruck;
use App\Models\Maintenance\CheckListLiftTrucks;
use App\Models\Maintenance\StorageCheckListDetailLiftTruck;
use App\Models\Maintenance\StorageLiftTruck;
use App\Models\Maintenance\StorageliftTruckAssigned;
use App\Traits\Api\General\SavePhotoInRegister;
use App\Traits\ResponseApi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Log;

class SaveCheckListController extends Controller
{
    use ResponseApi;
    use SavePhotoInRegister;

    public function saveCheckListLiftTruck(SaveCheckListMtnRequest $request)
    {
        DB::beginTransaction();
        try {
            $truck = $this->getLiftTruck($request['serialnumber']);
            $checkListTruck = $this->createNewCheckListTruck($truck, $request['supervisorname'], $request['horometro'], $request['comment']);
            $this->createDetailCheckListTruck($request, $checkListTruck, $truck);
            $this->saveKeyUser($request, $truck, $checkListTruck);
            $statusTruck = $this->getLiftTruck($request['serialnumber']);
            if ($statusTruck->statusid != 'MT-B') {
                $this->createAssignedToUser($truck);
            }
            DB::commit();
            return $this->handleSuccessResponse($truck, $checkListTruck);
        } catch (Exception $e) {
            $success = false;
            $error = $e->getMessage();
            Log::error($error);
            DB::rollback();
            return $this->getResponseWithErrors('saveCheckListLiftTruck', 'Error al procesar la solicitud.');
        }
    }

    private function handleSuccessResponse($truck, $checkListTruck)
    {
        $responseData = [
            'truck' => $truck->serialnumber,
            'checklistid' => $checkListTruck->id
        ];
        if ($truck->statusid == 'MT-B') {
            return $this->getResponseSuccessWithMessage('saveCheckListLiftTruck', $responseData,
                'El equipo no puede ser utilizado, favor de notificar a mantenimiento.', '0'
            );
        }
        return $this->getResponseSuccess('saveCheckListLiftTruck', $responseData);
    }

    public function getLiftTruck($serialnumber)
    {
        return StorageLiftTruck::controlAccessUser()
            ->where('serialnumber', '=', $serialnumber)
            ->select(['centerid', 'serialnumber', 'numbertruck', 'statusid', 'typeid'])
            ->first();
    }

    public function createNewCheckListTruck($truck, $supervisorname, $horometro, $comment)
    {
        return CheckListLiftTrucks::create([
            'centerid' => $truck->centerid,
            'serialnumber' => $truck->serialnumber,
            'supervisorname' => $supervisorname,
            'horometro' => $horometro,
            'auxiliary01' => $comment
        ]);
    }

    public function createDetailCheckListTruck($request, $checkListTruck, $truck): void
    {
        if ($request['checklistdetailid'] != "") {
            $total = count($request["checklistdetailid"]) - 1;
            for ($i = 0; $i <= $total; $i++) {
                CheckListDetailLiftTruck::create([
                    'centerid' => $checkListTruck->centerid,
                    'checklisttruckid' => $checkListTruck->id,
                    'checklistid' => $request['checklistid'],
                    'checklistdetailid' => $request['checklistdetailid'][$i],
                    'note' => $request['note'][$i],
                    'value01' => $request['value01'][$i],
                    'value02' => $request['value01'][$i],
                    'created_at' => Carbon::now(),
                ]);
                if ($request['value01'][$i] == '0') {
                    $this->updateTruckIfCriticalDetail($request['checklistdetailid'][$i], $truck);
                }
            }
        }
    }

    public function saveKeyUser(Request $request, StorageLiftTruck $truck, CheckListLiftTrucks $checkListTruck): void
    {
        if (!empty($request['file'])) {
            $pathFile = $this->createNewEvidenceForRegister($request['file'], $truck, 'Maintenance', 'StorageLiftTruck', 'Keys');

            $checkListTruck->auxiliary02 = $pathFile;
            $checkListTruck->save();
        }
    }

    public function updateTruckIfCriticalDetail($checklistdetailid, $truck): void
    {
        $typeDetailCheckList = StorageCheckListDetailLiftTruck::where('id', '=', $checklistdetailid)->select(['id', 'typeid'])->first();
        if ($typeDetailCheckList->typeid == 'TP-02') {
            if ($truck->statusid != 'MT-B') {
                $truck->statusid = 'MT-B';
                $truck->save();
            }
        }
    }

    public function createAssignedToUser($truck): void
    {
        StorageliftTruckAssigned::create([
            'centerid' => $truck->centerid,
            'username' =>Auth::user()->id,
            'serialnumber' => $truck->serialnumber,
        ]);
        $truck->statusid = 'MT-C';
        $truck->save();
    }

    public function saveKeySupervisor(SaveKeySupervisorRequest $request)
    {
        $checkListTruck = CheckListLiftTrucks::controlAccessUser()->where('id', $request['checklistid'])->first();
        $pathFile = $this->createNewEvidenceForRegister($request['file'], $checkListTruck, 'Maintenance', 'StorageLiftTruck', 'Keys');
        $checkListTruck->auxiliary03 = $pathFile;
        $checkListTruck->save();
        return $this->getResponseSuccess('saveKeySupervisor', 'El archivo se guardo correctamente.');
    }



}
