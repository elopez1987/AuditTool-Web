<?php

namespace App\Http\ControllersApp\Shipment\VerificationProcess;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Shipment\VerificationProcess\SearchLpnIDRequest;
use App\Models\Shipments\ShipmentContainer;
use App\Traits\ResponseApi;
use Carbon\Carbon;

class CloseProcessLpnController extends Controller
{
    use ResponseApi;

    public function index(SearchLpnIDRequest $request)
    {
        $lpn = ShipmentContainer::controlAccessUser()->where('lpnid', '=', $request['lpnid'])->first();
        if (!empty($lpn->closedate)) {
            return $this->getResponseWithErrors('saveFinishCheckLpn', 'El número de lpn ya está cerrado.');
        }
        $lpn->closedate = Carbon::now();
        $lpn->save();
        return $this->getResponseWithSuccess('saveFinishCheckLpn', 'Lpn cerrado con exito.');
    }


}
