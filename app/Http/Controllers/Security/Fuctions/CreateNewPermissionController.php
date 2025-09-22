<?php

namespace App\Http\Controllers\Security\Fuctions;

use App\Http\Controllers\BaseController;
use App\Models\Security\Fuctions\SecurityFunction;
use App\Models\Security\Permission\Permission;
use Illuminate\Http\Request;

class CreateNewPermissionController extends BaseController
{
    public function createNewPermissionForFunction(SecurityFunction $securityFunction, Request $request)
    {
        $permissions = $request->input('permisionid');
        if (! empty($permissions)) {
            $resposeSave = $this->savePermissions($permissions, $request, $securityFunction);
            if ($resposeSave) {
                return $resposeSave;
            }
        }
        return $this->redirectBackSuccessMessage('Permisos creados con exito!!');
    }

    private function savePermissions(array $permissions, Request $request, SecurityFunction $securityFunction)
    {
        $permissionNames = $request->input('permisionname');
        foreach ($permissions as $index => $name) {
            if (! empty($name)) {
                $formattedName = str_replace(' ', '', $name);
                $exists = Permission::where('name', $formattedName)->exists();
                if (! $exists) {
                    Permission::create([
                        'moduleid' => $securityFunction->moduleid,
                        'functionid' => $securityFunction->functionid,
                        'name' => $formattedName,
                        'description' => $permissionNames[$index] ?? '',
                        'guard_name' => 'web',
                    ]);
                } else {
                    return $this->errorResponse('El permiso ya existe');
                }
            }
        }
    }
}
