<?php

namespace App\Http\ControllersApp\Configuration\Status;

use App\Http\Controllers\Controller;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Traits\ResponseApi;

class StatusController extends Controller
{
   use ResponseApi;

    public function getStatusForSchemeRUS()
    {
        $data = SystemStatus::whereSchemaid('RUS')
            ->select(['statusid', 'name'])->get();
        return $this->getResponseSuccess('getStatusForScheme', $data);
    }


}
