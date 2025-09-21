<?php

namespace App\Http\Controllers\Security\Roles;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Security\Roles\StorageCategoryRoleRequest;
use App\Models\Security\Roles\StorageCategoryRole;
use Spatie\Permission\Models\Role;

class StorageCategoryRoleController extends BaseController
{
    public function conceptajust($id)
    {
        $role = Role::findOrFail($id);
        $details = StorageCategoryRole::where('roleid', $id)->get();
        return view(
            'Security.Roles.Parameters.conceptajust',
            compact('role', 'details')
        );
    }

    public function saveconceptajust(StorageCategoryRoleRequest $request, $id)
    {
        $role = Role::findOrFail($id);
        StorageCategoryRole::create([
            'centerid' => $role->centerid,
            'roleid' => $role->id,
            'categoryincid' => $request['categoryincid'],
            'categoryincdetailid' => $request['categoryincdetailid'],
        ]);
        return $this->redirectBackSuccess();
    }

    public function destroy($id)
    {
        StorageCategoryRole::destroy($id);
        return $this->redirectBackSuccessMessage('Registro eliminado con exito.');
    }
}
