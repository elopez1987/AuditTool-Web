<?php

namespace App\Traits\Web\Maintenance;

use App\Models\Maintenance\CheckListLiftTrucks;
use DB;

trait QueryCheckListLiftTrucks
{
    public function getDetailCheckListForTruck($truckChecklist, $id): array
    {
        return DB::select(
            "select storagechecklistdetaillifttrucks.checklistid,storagechecklistdetaillifttrucks.id as checklistdetailid,
            description,typeid,checklistlifttruckdetails.value01,checklistlifttruckdetails.note
            from storagechecklistdetaillifttrucks
            left join checklistlifttruckdetails on storagechecklistdetaillifttrucks.id = checklistlifttruckdetails.checklistdetailid
                 where storagechecklistdetaillifttrucks.checklistid in (select id from storagechecklistslifttrucks s
                     where s.typeid in (select s.typeid from storagelifttrucks s2
                          where serialnumber=? and centerid=?))
                and statusid='E'
                and storagechecklistdetaillifttrucks.centerid = ?
                and checklistlifttruckdetails.checklisttruckid = ?;",
            [$truckChecklist->serialnumber, $truckChecklist->centerid, $truckChecklist->centerid, $id]
        );
    }

    public function getAllCheckListTruck($request)
    {
        return CheckListLiftTrucks::controlAccessUser()
            ->leftJoin('users', 'checklistlifttrucks.createuser', '=', 'users.username')
            ->leftJoin('storagelifttrucks', 'checklistlifttrucks.serialnumber', '=', 'storagelifttrucks.serialnumber')
            ->leftJoin('systemstatus', 'storagelifttrucks.typeid', '=', 'systemstatus.statusid')
            ->ForSerialNumber($request->get('serialnumber'))
            ->ForNumberTruck($request->get('numbertruck'))
            ->ForWorkday($request->get('workday'))
            ->select([
                'checklistlifttrucks.id', 'checklistlifttrucks.centerid', 'checklistlifttrucks.serialnumber',
                'storagelifttrucks.numbertruck', 'systemstatus.name as type',
                'checklistlifttrucks.horometro', 'users.name as monta',
                'checklistlifttrucks.supervisorname', 'checklistlifttrucks.created_at as created',
            ])->orderBy('checklistlifttrucks.id', 'desc')->take(200)->get();
    }
}
