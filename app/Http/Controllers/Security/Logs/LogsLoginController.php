<?php

namespace App\Http\Controllers\Security\Logs;

use App\Http\Controllers\BaseController;
use App\Models\Security\Logs\AuthenticationAuditLog;

class LogsLoginController extends BaseController
{
    //	Logs de Authentication
    public function logauthentication()
    {
        return view('Security.LogAuthentication.index');
    }

    public function logsloginAuthentication()
    {
        $data = AuthenticationAuditLog::orderBy('id', 'desc')->get()->take(2000);

        return $this->showDataToView($data, '');
    }
}
