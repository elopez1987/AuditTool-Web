<?php

namespace App\Http\Controllers\Security\Fuctions;

use App\Http\Controllers\BaseController;
use App\Models\Security\Fuctions\SecurityFunction;
use App\Models\Security\Fuctions\SecurityFunctionsMenuStructure;
use App\Models\Security\MenuStructure\SecurityMenu;
use Illuminate\Http\Request;

class SecurityFunctionsMenuController extends BaseController
{
    public function savefunctionsformenustructure(Request $request, SecurityMenu $securitymenustructure)
    {
        if ($request['functionid'] !== '') {
            $total = count($request['functionid']) - 1;
            for ($i = 0; $i <= $total; $i++) {
                $module = SecurityFunction::findOrFail($request['functionid'][$i]);
                SecurityFunctionsMenuStructure::create([
                    'menustrucid' => $securitymenustructure->menustrucid,
                    'functionid' => $request['functionid'][$i],
                    'moduleid' => $module->moduleid,
                ]);
            }
        }
        return $this->redirectBackSuccessMessage('Modulos agregados con exito.');
    }

    public function deletefunctionformenu(Request $request)
    {
        SecurityFunctionsMenuStructure::destroy($request['id']);
        return $this->responseSuccessAjax('', '');
    }
}
