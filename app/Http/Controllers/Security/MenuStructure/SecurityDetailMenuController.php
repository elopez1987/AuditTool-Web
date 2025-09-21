<?php

namespace App\Http\Controllers\Security\MenuStructure;

use App\Http\Controllers\BaseController;
use App\Models\Security\Fuctions\SecurityFunctionsMenuStructure;
use App\Models\Security\MenuStructure\SecurityDetailsMenuStructure;
use App\Models\Security\MenuStructure\SecurityMenu;
use DB;
use Exception;
use Illuminate\Http\Request;
use Log;

class SecurityDetailMenuController extends BaseController
{
    public function savedatailmenustructure(Request $request, SecurityMenu $securitymenustructure)
    {
        if ($request['moduleid'] !== '') {
            $total = count($request['moduleid']) - 1;
            for ($i = 0; $i <= $total; $i++) {
                SecurityDetailsMenuStructure::create([
                    'menustrucid' => $securitymenustructure->menustrucid,
                    'moduleid' => $request['moduleid'][$i],
                ]);
            }
        }
        return $this->redirectBackSuccessMessage('Modulos agregados con exito.');
    }

    public function destroyDetailMenu(Request $request)
    {
        DB::beginTransaction();
        try {
            $menudetail = SecurityDetailsMenuStructure::findOrFail($request['id']);
            $menudetail->delete();
            SecurityFunctionsMenuStructure::where('moduleid', '=', $menudetail->moduleid)
                ->where('menustrucid', '=', $request['menustrucid'])->delete();
            DB::commit();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
        }
        return $this->responseSuccessAjax('', '');
    }
}
