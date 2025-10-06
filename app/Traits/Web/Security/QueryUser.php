<?php

namespace App\Traits\Web\Security;

use App\Models\Security\Roles\Role;
use App\Models\Security\Users\AccessCarrier;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\Carriers\ShipmentCarrier;
use App\Models\Yard\Customer\OrderCustomer;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait QueryUser
{
    public function getRoles()
    {
        return Role::controlAccessUser()->where('statusid', '=', 'E')
            ->select(['description', 'name', 'id'])->get();
    }

    public function getAllUsers($request)
    {
        $centers = $this->getCentersForUser();

        return User::withTrashed()->join('systemstatus', 'users.statusid', '=', 'systemstatus.statusid')
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->leftJoin('securityaccesswarehouses', 'users.username', '=', 'securityaccesswarehouses.username')
            ->leftJoin('securityuserssessions', 'users.username', '=', 'securityuserssessions.username')
            ->select(['users.name', 'users.id', 'users.email', 'users.uuiduser',
                'users.username', 'roles.description as role', 'securityuserssessions.lastsessiondate as lastlogin',
                'systemstatus.name as status', 'users.created_at', 'users.updated_at',
            ])
            ->Name($request->get('name'))
            ->UserName($request->get('userid'))
            ->Role($request->get('role_id'))
            ->ForStatus($request->get('statusid'))
            ->whereIn('securityaccesswarehouses.centerid', $centers)
            ->distinct();
    }

    public function getRoleForUser($id)
    {
        return DB::table('model_has_roles')->where('model_id', '=', $id)->select('role_id')->first();
    }

    public function getCarriersForUser($user)
    {
        return AccessCarrier::where('username', $user->username)
            ->leftJoin('shipmentcarriers', 'securityaccesscarriers.carrierid', 'shipmentcarriers.id')
            ->select(['securityaccesscarriers.recordid', 'shipmentcarriers.name as carrier'])->get();
    }

    public function getCenterForCarrier($request)
    {
        return ShipmentCarrier::where('id', '=', $request['carrierid'])->value('centerid');
    }

    public function getCenterForCustomer($customerid)
    {
        return OrderCustomer::controlAccessUser()->where('id', '=', $customerid)->value('centerid');
    }

    private function getCentersForUser(): array
    {
        return (new SecurityAccessWarehouse)->where('username', Auth::user()->username)
            ->select('centerid')->get()->toArray();
    }
}
