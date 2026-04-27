<?php

namespace App\Http\Controllers\Shipments\Monitors\Traffic;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class BackLogController extends BaseController
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->getDataBacklog();

            return $this->showDataToView($data, '');
        }

        return view('Shipments.OrderMonitor.Backlog.index');
    }

    public function getDataBacklog() {}
}
