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
		$user = User::create($request->validated() + ['password' => bcrypt($request['password'])]);
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
			'centerid' => $centerid
		]);
	}


	public function assignCustomerToUser($request, $centerid)
	{
		SecurityAccessCustomer::create([
			'centerid' => $centerid,
			'username' => $request['username'],
			'customerid' => $request['customerid']
		]);
	}

}
