<?php

namespace App\Traits\Web\Maintenance;

use App\Models\Maintenance\StorageAssignLiftTrucks;
use App\Models\Maintenance\StorageLiftTruck;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

trait QueryStorageLiftTrucks
{
    public function getInformationLiftTrucks($request)
    {
        return StorageLiftTruck::controlAccessUser()
            ->with(
                [
                    'center' => function ($q) {
                        $q->select('centerid', 'name');
                    },
                    'type' => function ($q) {
                        $q->select('statusid', 'name');
                    },
                    'model' => function ($q) {
                        $q->select('statusid', 'name');
                    },
                    'brand' => function ($q) {
                        $q->select('statusid', 'name');
                    },
                    'status' => function ($q) {
                        $q->select('statusid', 'name');
                    },
                    'user' => function ($q) {
                        $q->select('username', 'name');
                    },
                ],
            )
            ->SerialNumber($request->get('serialnumber'))
            ->NumberTruck($request->get('numbertruck'))
            ->Status($request->get('statusid'))
            ->select('storagelifttrucks.*');
    }

    public function getUserForAssign(): Collection
    {

        return DB::table('users as u')
            ->select('u.id', 'u.username', 'u.name')
            ->leftJoin('model_has_roles as mhr', 'u.id', '=', 'mhr.model_id')
            ->leftJoin('roles as r', 'mhr.role_id', '=', 'r.id')
            ->where('u.statusid', 'E')
            ->whereRaw("CHARINDEX('ROL-01', r.configurationflag) > 0")
            ->get();

    }

    public function getStorageAssignLiftTrucks($id)
    {
        return StorageAssignLiftTrucks::controlAccessUser()
            ->where('typeid', $id)
            ->select(['username', 'createuser', 'created_at', 'id'])->get();
    }
}
