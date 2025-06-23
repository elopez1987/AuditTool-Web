<?php

namespace App\Http\ControllersApp\Yard\Locations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Yard\Location\LocationForZoneRequest;
use App\Models\Yard\StorageLocation;
use App\Traits\ResponseApi;

class LocationController extends Controller
{
    use ResponseApi;

    public function getLocationForZone(LocationForZoneRequest $request)
    {
        $locations = StorageLocation::controlAccessUser()
            ->where('zoneid',$request['zoneid'])
            ->select(['id','name'])->get();
        return $this->getResponseSuccess('getLocationForZone',$locations);

    }
}
