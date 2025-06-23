<?php

namespace App\Http\ControllersApp\Maintanance;

use App\Http\Controllers\Controller;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Traits\ResponseApi;
use App\User;
use Illuminate\Support\Facades\Auth;

class SearchSupervisorController extends Controller
{
    use ResponseApi;


    public function getSupervisors()
    {
        $center = SecurityAccessWarehouse::where('username', Auth::user()->username)->value('centerid');
        $supervisors = User::leftJoin('securityaccesswarehouses', 'users.username', '=', 'securityaccesswarehouses.username')
            ->select(['users.name', 'users.id'])
            ->where('securityaccesswarehouses.centerid', '=', $center)
            ->where('users.statusid', '=', 'E')
            ->whereNotNull('configurationflag')
            ->whereRaw("charindex('USR-02', configurationflag) > 0")
            ->distinct()
            ->get();
        return $this->getResponseSuccess('', $supervisors);
    }
}
