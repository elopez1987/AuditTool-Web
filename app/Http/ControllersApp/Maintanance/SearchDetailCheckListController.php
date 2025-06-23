<?php

namespace App\Http\ControllersApp\Maintanance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Maintanance\SearchDetailCheckListRequest;
use App\Models\Maintenance\CheckListTruckErrorLog;
use App\Models\Maintenance\StorageAssignLiftTrucks;
use App\Models\Maintenance\StorageCheckListDetailLiftTruck;
use App\Models\Maintenance\StorageLiftTruck;
use App\Traits\ResponseApi;
use Illuminate\Support\Facades\Auth;

class SearchDetailCheckListController extends Controller
{
    use ResponseApi;

    public function getDataLiftTruck(SearchDetailCheckListRequest $request)
    {
        $truck = $this->getLiftTruck($request['serialnumber']);
        if (empty($truck)) {
            return $this->getResponseWithErrors('getDataLiftTruck',
                'No se encuentran datos del equipo.');
        }
        if ($truck->statusid == 'MT-B' || $truck->statusid == 'MT-C') {
            $this->CreateErrorLog($truck);
            return $this->getResponseWithErrors('getDataLiftTruck',
                'El equipo se encuentra bloqueado  o asignado y no puede ser utilizado.');
        }
        $typeAssignedToUser = $this->assignTruckToUser($truck);
        if (empty($typeAssignedToUser)) {
            return $this->getResponseWithErrors('getDataLiftTruck',
                'No estas habilitado para usar este equipo.');
        }
        $checkList = $this->getCheckListDetailLiftTrucks($truck);
        return $this->getResponseSuccess('getDataLiftTruck',
            ['truck' => [
                'serialnumber' => $truck->serialnumber,
                'numbertruck' => $truck->numbertruck],
                'checklist' => $checkList]);
    }

    public function assignTruckToUser($truck)
    {
        return StorageAssignLiftTrucks::where('typeid', '=', $truck->typeid)
            ->where('username', '=', Auth::user()->username)->first();
    }

    public function CreateErrorLog($truck): void
    {
        CheckListTruckErrorLog::create([
            'centerid' => $truck->centerid,
            'serialnumber' => $truck->serialnumber,
        ]);
    }

    public function getLiftTruck($serialnumber)
    {
        return StorageLiftTruck::controlAccessUser()
            ->where('serialnumber', '=', $serialnumber)
            ->select(['centerid', 'serialnumber', 'numbertruck', 'statusid', 'typeid'])
            ->first();
    }

    public function getCheckListDetailLiftTrucks(StorageLiftTruck $truck)
    {
        return StorageCheckListDetailLiftTruck::where('centerid', $truck->centerid)
            ->where('statusid', 'E')
            ->select(['checklistid', 'id as checklistdetailid', 'description', 'auxiliary01'])
            ->whereHas('checklist', function ($q) use ($truck) {
                $q->where('statusid', 'E');
                $q->where('typeid', $truck->typeid);
                $q->where('centerid', $truck->centerid);
            })
            ->OrderBy('auxiliary01')
            ->inRandomOrder()
            ->get();
    }
}
