<?php

namespace App\Http\Controllers\Services\Yard;

use App\Http\Controllers\Controller;
use App\Traits\ResponseApi;
use App\Traits\Yard\QueryReceiptUnits;
use Illuminate\Http\Request;

class MonitorBoxesController extends Controller
{
    use ResponseApi;
    use QueryReceiptUnits;
    public function getDataMonitor(Request $request)
    {
        $data =  $this->getDataMonitorUnit($request);
        return $this->getResponseSuccess('getDataMonitor',$data);
    }


}
