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

        $data = StorageUserDevice::join('users', 'securitydeviceforuser.username', 'users.id')
            ->select(['users.name as user', 'securitydeviceforuser.serialnumber', 'securitydeviceforuser.created_at'])
            ->ForSerialNumber($request->input('serialnumber'));

        return $this->showDataToView($data, '');

    }
}
