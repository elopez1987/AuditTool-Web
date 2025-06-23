<?php

namespace App\Http\ControllersApp\Yard\OriginTruck;

use App\Http\Controllers\Controller;
use App\Models\Yard\StorageUnitType;
use App\Traits\ResponseApi;

class OriginTruckController extends Controller
{
    use ResponseApi;

    public function getUnitTypesOrg()
    {
        $unitTypes = StorageUnitType::controlAccessUser()->where('statusid', '=', 'E')
            ->select(['id', 'description'])->get();
        return $this->getResponseSuccess('getUnitTypesOrg', $unitTypes);
    }
}
