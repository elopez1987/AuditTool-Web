<?php

namespace App\Traits\Web\Maintenance;

use App\Models\Maintenance\StorageAssignLiftTrucks;
use App\Models\Maintenance\StorageLiftTruck;
use DB;

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
                ]
            )
            ->SerialNumber($request->get('serialnumber'))
            ->NumberTruck($request->get('numbertruck'))
            ->Status($request->get('statusid'))
            ->select('storagelifttrucks.*');
    }

    /**
     * @param $centerid
     * @param $id
     *
     * @return array
     */
    public function getUserForAssign($centerid, $id): array
    {
        return DB::select("select id,username,name from users
                        where id in (select model_id from model_has_roles mhr
                                                     where role_id in (select value02 from systemdetailparameter s
                                                                                      where parameterid='ROLE-TRUCK' and value01=?))
        and statusid='E'
        and username not in (select username from storageassignlifttrucks s2 where typeid=?)", [$centerid, $id]);
    }

    public function getStorageAssignLiftTrucks($id)
    {
        return StorageAssignLiftTrucks::controlAccessUser()
            ->where('typeid', $id)
            ->select(['username', 'createuser', 'created_at', 'id'])->get();
    }
}
