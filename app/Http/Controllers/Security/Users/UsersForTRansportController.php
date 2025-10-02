<?php

namespace App\Http\Controllers\Security\Users;

use App\Http\Controllers\BaseController;
use App\Models\Security\Users\AccessCarrier;
use Illuminate\Support\Facades\DB;

class UsersForTRansportController extends BaseController
{
	public function index()
	{
		return view('Security.UsersForTransports.index');
	}


	public function usersfortransportdata()
	{
		$data = AccessCarrier::query()
			->leftJoin('users as u', 'securityaccesscarriers.username', '=', 'u.username')
			->leftJoin('shipmentcarriers as c', 'securityaccesscarriers.carrierid', '=', 'c.id')
			->where('u.statusid', 'E')
			->select([
				'c.carrierid',
				'c.name as carriername',
				'u.username',
				DB::raw('UPPER(u.name) as name'),
				'u.email'
			])->get();
		return $this->showDataToView($data, '');
	}

}
