<?php

namespace App\Http\Controllers\Security\Logs;

use App\Http\Controllers\BaseController;
use App\Models\Security\Logs\SuspiciousActivity;

class LogSuspiciousController extends BaseController
{
    public function logsuspicious()
    {
        return view('Security.LogSuspicious.index');
    }

    public function getLogsuspicious()
    {
        $data = SuspiciousActivity::orderBy('id', 'desc')->get()->take(2000);

        return $this->showDataToView($data, '');
    }
}
