<?php

namespace App\Http\ControllersApp\Yard\Complementos;

use App\Http\Controllers\Controller;
use App\Http\Resources\Inventory\InventoryPartNumberResource;
use App\Http\Resources\Security\UserResource;
use App\Http\Resources\Yard\StorageAreasResource;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Yard\StorageAreas;
use App\Traits\ResponseApi;
use App\User;
use Illuminate\Http\Request;

class ComplementosController extends Controller
{

    use ResponseApi;

    public function getUsersReponsible(Request $request)
    {
        $users = User::select(['id', 'name'])
            ->where('statusid', '=', 'E')
            ->whereRaw("charindex('USR-03', configurationflag) > 0")
            ->get();
        return $this->showAll('getUsersReponsible', UserResource::collection($users));
    }


    public function getAreasResponsable(Request $request)
    {
        $areas = StorageAreas::controlAccessUser()->where('statusid', '=', 'E')->get();
        return $this->showAll('getAreasResponsable', StorageAreasResource::collection($areas));
    }


    public function getMaterialContensForUnits(Request $request)
    {
        $data = InventoryPartnumber::controlAccessUser()->where('statusid', '=', 'E')
            ->whereRaw("charindex('PIV-01', configurationflag) > 0")
            ->get();
        return $this->showAll('getMaterialContensForUnits', InventoryPartNumberResource::collection($data));
    }


}
