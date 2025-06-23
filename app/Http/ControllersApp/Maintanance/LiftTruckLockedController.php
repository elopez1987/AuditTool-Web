<?php

namespace App\Http\ControllersApp\Maintanance;

use App\Http\Controllers\Controller;
use App\Models\Maintenance\CheckListDetailLiftTruck;
use App\Models\Maintenance\CheckListLiftTrucks;
use App\Models\Maintenance\StorageCheckListActiveTruck;
use App\Models\Maintenance\StorageLiftTruck;
use App\Models\Maintenance\StorageliftTruckAssigned;
use App\Traits\EvidencePhotoTrait;
use App\Traits\ResponseApi;
use App\User;
use DB;
use Exception;
use Illuminate\Http\Request;

class LiftTruckLockedController extends Controller
{
    use ResponseApi;
    use EvidencePhotoTrait;

    public function index()
    {
        $data = StorageLiftTruck::controlAccessUser()
            ->where('statusid', '=', 'MT-B')
            ->select(['centerid', 'serialnumber', 'numbertruck', 'updated_at'])->get();
        return $this->getResponseSuccess('getLiftTruckLocked', $data);
    }

    public function edit($serialnumber)
    {
        $data = CheckListDetailLiftTruck::query()->select([
                'checklistlifttruckdetails.checklisttruckid',
                'checklistlifttruckdetails.checklistid',
                'checklistlifttruckdetails.checklistdetailid',
                'storagechecklistdetaillifttrucks.description'
            ])
            ->leftJoin('storagechecklistdetaillifttrucks', function ($join) {
                $join->on('checklistlifttruckdetails.checklistdetailid', '=', 'storagechecklistdetaillifttrucks.id')
                    ->on('checklistlifttruckdetails.centerid', '=', 'storagechecklistdetaillifttrucks.centerid');
            })
            ->where('checklistlifttruckdetails.value02', '0')
            ->whereIn('checklistlifttruckdetails.checklisttruckid', function ($query) use ($serialnumber) {
                $query->select(DB::raw('TOP 1 id'))
                    ->from('checklistlifttrucks')
                    ->where('serialnumber', $serialnumber)
                    ->orderByDesc('id');
            })->get();
        return $this->getResponseSuccess('getLiftTruckLocked', $data);
    }

    public function update(Request $request, $serialnumber)
    {
        if ($request['checklistdetailid'] != "") {
            DB::beginTransaction();
            try {
                $truck = StorageLiftTruck::controlAccessUser()->where('serialnumber', '=', $serialnumber)->first();
                $total = count($request["checklistdetailid"]) - 1;
                if (!empty($request['filekey'])) {
                    $pathFileKey = $this->saveFileToModelBase64($request['filekey'], 'StorageLiftTruck', $truck, 'Maintenance', 'KEY', 'Table');
                }
                for ($i = 0; $i <= $total; $i++) {
                    if (!empty($request['files']) && $request['files'][$i] != '0') {
                        $evidence = $this->saveFileToModelBase64($request['files'][$i], 'StorageLiftTruck', $truck, 'Maintenance', 'EVD', 'Table');
                    } else {
                        $evidence = '';
                    }
                    StorageCheckListActiveTruck::create([
                        'centerid' => $truck->centerid,
                        'serialnumber' => $request['serialnumber'],
                        'checklistid' => $request['checklistid'],
                        'checklisttruckid' => $request['checklisttruckid'],
                        'checklistdetailid' => $request['checklistdetailid'][$i],
                        'responsecheck' => $request['value01'][$i],
                        'evidencekey' => $pathFileKey,
                        'evidencepath' => $evidence
                    ]);
                    if ($request['value01'][$i] == '1') {
                        $keyDetail = CheckListDetailLiftTruck::where('checklisttruckid', '=', $request['checklisttruckid'])
                            ->where('checklistdetailid', '=', $request['checklistdetailid'][$i])
                            ->select(['id', 'value02'])->first();
                        $keyDetail->value02 = '1';
                        $keyDetail->save();
                    }
                }
                $totalErrors = CheckListDetailLiftTruck::where('checklisttruckid', $request['checklisttruckid'])->where('value02', '=', 0)->count();
                if ($totalErrors > 0) {
                    return $this->getResponseWithErrors('UpdateCheckListTruck', 'El equipo aun no puede ser utilizado');
                } else {
                    $truck->statusid = 'MT-A';
                    $truck->save();
                    $checklistUser = CheckListLiftTrucks::controlAccessUser()->where('id', '=', $request['checklisttruckid'])->value('createuser');
                    $user = User::where('username', '=', $checklistUser)->select('id')->first();
                    $userAssigned = StorageliftTruckAssigned::controlAccessUser()
                        ->where('username', '=', $user->id)
                        ->where('statusid', '=', 'MT-C')->first();
                    if (empty($userAssigned)) {
                        StorageliftTruckAssigned::create([
                            'centerid' => $truck->centerid,
                            'serialnumber' => $truck->serialnumber,
                            'username' => $user->id
                        ]);
                        $truck->statusid = 'MT-C';
                        $truck->save();
                    }
                }
                DB::commit();
                $success = true;
            } catch (Exception) {
                $success = false;
                DB::rollback();
            }
            if ($success) {
                return $this->getResponseWithSuccess('UpdateCheckListTruck', 'Equipo liberado con exito');
            } else {
                return $this->getResponseWithErrors('UpdateCheckListTruck', 'Error al procesar la solicitud, intente mas tarde.');
            }
        } else {
            return $this->getResponseWithErrors('UpdateCheckListTruck', 'El check list es un valor obligatorio.');
        }
    }

}
