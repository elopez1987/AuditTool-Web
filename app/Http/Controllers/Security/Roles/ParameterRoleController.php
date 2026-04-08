<?php

namespace App\Http\Controllers\Security\Roles;

use App\Http\Controllers\BaseController;
use App\Models\Security\Roles\Role;
use Illuminate\Http\Request;

class ParameterRoleController extends BaseController
{
    public function parameters($id)
    {
        $role = Role::findOrFail($id);
        $parameters = $this->getStatusForSchema('ROL');

        return view('Security.Roles.Parameters.create', compact('role', 'parameters'));
    }

    public function store(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->update(['configurationflag' => $request->input('parameter')]);

        return $this->redirectBackSuccess();
    }
}
