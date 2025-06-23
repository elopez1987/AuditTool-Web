<?php

namespace App\Http\ControllersApp\Yard\TypeTruck;

use App\Http\Controllers\Controller;
use App\Models\Yard\ShipmentTruck\ShipmentTruck;
use App\Traits\ResponseApi;

class TypeTruckController extends Controller
{
   use ResponseApi;

    public function getTypeTrucksForCenter()
    {
        $trucks = ShipmentTruck::controlAccessUser()
            ->where('statusid', '=', 'E')
            ->select(['id as truckid', 'name'])->get();
        return $this->getResponseSuccess('getTypeTrucksForCenter', $trucks);
    }

}
