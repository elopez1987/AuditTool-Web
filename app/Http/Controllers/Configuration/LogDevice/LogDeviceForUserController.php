<?php

namespace App\Http\Controllers\Configuration\LogDevice;

use App\Http\Controllers\BaseController;
use App\Models\Security\Users\StorageUserDevice;
use Illuminate\Http\Request;

class LogDeviceForUserController extends BaseController
{
    public function index()
    {
        $this->authorize('logdeviceuser');
        return view('Configuration.LogDeviceUser.index');
    }

    public function getDevicesForUser(Request $request)
    {
        if ($request->ajax() && $request['serialnumber'] != '') {
            $data = StorageUserDevice::leftJoin('users', 'securitydeviceforuser.username', 'users.id')
                ->select(['users.name as user', 'securitydeviceforuser.serialnumber', 'securitydeviceforuser.created_at'])
                ->ForSerialNumber($request->get('serialnumber'))
                ->get();
            return $this->showDataToView($data, []);
        }
    }


}
