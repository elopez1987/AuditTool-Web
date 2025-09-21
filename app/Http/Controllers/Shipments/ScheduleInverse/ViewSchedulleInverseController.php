<?php

namespace App\Http\Controllers\Shipments\ScheduleInverse;

use App\Http\Controllers\BaseController;

class ViewSchedulleInverseController extends BaseController
{
    public function index()
    {
        return view('Shipments.ViewSchedulleInverse.index');
    }
}
