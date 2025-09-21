<?php

namespace App\Traits\Web\Maintenance;

use App\Models\Maintenance\StorageCheckListLiftTrucks;

trait QueryStorageCheckLists
{
    public function getAllCheckList($request)
    {
        return StorageCheckListLiftTrucks::controlAccessUser()
            ->with(
                [
                    'center' => function ($q) {
                        $q->select('centerid', 'name');
                    },
                    'status' => function ($q) {
                        $q->select('statusid', 'name');
                    },
                    'type' => function ($q) {
                        $q->select('statusid', 'name');
                    },
                ]
            )
            ->ForName($request->get('name'))
            ->select('storagechecklistslifttrucks.*');
    }
}
