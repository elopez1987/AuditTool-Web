<?php

namespace App\Http\ControllersApp\Yard\Carriers;

use App\Http\Controllers\Controller;
use App\Models\Yard\Carriers\ShipmentCarrier;
use App\Traits\ResponseApi;

class CarriersController extends Controller
{

    use ResponseApi;
    public function getCarrierForCenter()
    {
        $carriers = ShipmentCarrier::controlAccessUser()
            ->where('statusid', '=', 'E')
            ->select(['centerid', 'id as carrierid', 'name'])->get();
        return $this->getResponseSuccess('getCarrierForCenter', $carriers);
    }
}
