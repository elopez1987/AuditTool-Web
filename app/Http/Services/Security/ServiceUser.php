<?php

namespace App\Http\Services\Security;

use App\Models\Security\Users\AccessCarrier;
use App\Models\Security\Users\SecurityAccessCustomer;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;

class ServiceUser
{
    public function createNewUser($request)
    {
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
            'username' => $request['username'],
            'auxiliary01' => $request['auxiliary01'],
            'auxiliary02' => $request['auxiliary02'],
            'auxiliary03' => $request['auxiliary03'],
            'auxiliary04' => $request['auxiliary04'],
            'auxiliary05' => $request['auxiliary05'],
        ]);

        SecurityAccessWarehouse::create([
            'centerid' => $request['centerid'],
            'warehouseid' => $request['warehouseid'],
            'username' => $user->username,
        ]);
        return $user;
    }

    public function assignCarrierToUser($request, $centerid)
    {
        AccessCarrier::create([
            'carrierid' => $request['carrierid'],
            'username' => $request['username'],
            'centerid' => $centerid,
        ]);
    }

    public function assignCustomerToUser($request, $centerid)
    {
        SecurityAccessCustomer::create([
            'centerid' => $centerid,
            'username' => $request['username'],
            'customerid' => $request['customerid'],
        ]);
    }
}
