<?php

namespace App\Traits\Web\Shipments;

use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Shipments\StorageDoorStage;
use App\Models\Yard\StorageLocation;
use Auth;

trait QueryStorageDoorStages
{
    public function getAllStagesForCenter()
    {
        return StorageLocation::controlAccessUser()->with(['center', 'status'])
            ->whereHas('zone', function ($q) {
                $q->where('zoneid', '=', 'ST');
            })
            ->select(['id', 'zoneid', 'name', 'centerid', 'statusid'])
            ->get();
    }

    public function getAllDoorsForStage($id)
    {
        $center = SecurityAccessWarehouse::where('username', Auth::user()->username)->value('centerid');

        return StorageLocation::controlAccessUser()
            ->select(['id', 'name'])
            ->whereIn('zoneid', function ($query) use ($center) {
                $query->select('id')
                    ->from('storagezones')
                    ->where('zoneid', 'CO')
                    ->where('centerid', $center);
            })
            ->whereNotIn('id', function ($query) use ($id) {
                $query->select('doorid')
                    ->from('storagedoorstages')
                    ->where('stageid', $id);
            })->get();
    }

    public function getStagesForDoor($id)
    {
        return StorageDoorStage::select(['storagedoorstages.id', 'storagelocations1.name as door', 'storagelocations2.name as stage'])
            ->leftJoin('storagelocations as storagelocations1', function ($join) {
                $join->on('storagedoorstages.doorid', '=', 'storagelocations1.id')
                    ->on('storagedoorstages.centerid', '=', 'storagelocations1.centerid');
            })
            ->leftJoin('storagelocations as storagelocations2', function ($join) {
                $join->on('storagedoorstages.stageid', '=', 'storagelocations2.id')
                    ->on('storagedoorstages.centerid', '=', 'storagelocations2.centerid');
            })
            ->where('storagedoorstages.stageid', '=', $id)->get();
    }
}
