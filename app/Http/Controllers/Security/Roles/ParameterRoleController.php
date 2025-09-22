<?php

namespace App\Http\Controllers\Security\Roles;

use App\Http\Controllers\Controller;
use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\Security\Roles\Role;
use Illuminate\Http\Request;

class ParameterRoleController extends Controller
{
    public function registercheckforchecklistverific(Request $request)
    {
        $role = Role::where('id', $request['id'])->first();
        SystemDetailParameter::updateOrCreate([
            'parameterid' => 'ROLE-TRUCK',
            'value01' => $role->centerid,
            'value02' => $role->id,
        ]);
    }

    public function deletecheckforchecklistverific(Request $request)
    {
        SystemDetailParameter::where('value02', $request['id'])
            ->where('parameterid', 'ROLE-TRUCK')->delete();
    }
}
