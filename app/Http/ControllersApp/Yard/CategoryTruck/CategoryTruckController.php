<?php

namespace App\Http\ControllersApp\Yard\CategoryTruck;

use App\Http\Controllers\Controller;
use App\Models\Yard\ShipmentTruckCategory;
use App\Traits\ResponseApi;

class CategoryTruckController extends Controller
{
    use ResponseApi;

    public function getCategoryTruckForCenter()
    {
        $categories = ShipmentTruckCategory::where('statusid', '=', 'E')
            ->select(['truckcategoryid', 'description'])->get();
        return $this->getResponseSuccess('getCarrierForCenter', $categories);
    }

}
