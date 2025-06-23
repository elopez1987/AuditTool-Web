<?php

namespace App\Http\ControllersApp\Configuration\Reasons;

use App\Http\Controllers\Controller;
use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\StorageReason;
use App\Traits\ResponseApi;
use Illuminate\Support\Facades\Auth;

class ReasonsController extends Controller
{
    use ResponseApi;

    public function getRazonCodes()
    {
        $centerid = SecurityAccessWarehouse::where('username', Auth::user()->username)->value('centerid');
        $code = SystemDetailParameter::where('parameterid', '=', 'PARAM-CONCEPT')
            ->where('value01', '=', $centerid)->select('value02')->get()->toArray();
        $codes = StorageReason::whereIn('id', $code)
            ->select(['id as razonstatusid', 'name'])->get();
        return $this->getResponseSuccess('getRazonCodes', $codes);
    }

    public function getCodesTypeIn()
    {
        $data = StorageReason::where('type','=','IN')->select(['id as razonstatusid', 'name'])
            ->where('statusid', '=', 'E')->get();
        return $this->getResponseSuccess('getCodes', $data);
    }


}
