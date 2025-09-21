<?php

namespace App\Traits\Web\Security;

use App\Models\Security\MenuStructure\SecurityMenu;
use DB;

trait QueryMenuStructure
{
    public function getAllMenuStructure()
    {
        return SecurityMenu::controlAccessUser()->with([
            'status' => function ($q) {
                $q->select('statusid', 'name');
            },
            'center' => function ($q) {
                $q->select('centerid', 'name');
            },
        ])->select('securitymenustructure.*');
    }

    public function getModulesForMenu($securitymenustructure)
    {
        return DB::select('select a.moduleid,a.description
                from securitymodules a
                where a.moduleid not in
                      (select moduleid from securitydetailsmenustructure
                                       where menustrucid=?)', [$securitymenustructure->menustrucid]);
    }

    public function getFuctionsForMenu($securitymenustructure)
    {
        return DB::select(' select moduleid,functionid,description
                from securityfunctions
                where moduleid in (select moduleid from securitydetailsmenustructure s where menustrucid=?)
                and functionid not in (select functionid from securityfunctionsmenustructure s2 where menustrucid=?)
            ', [$securitymenustructure->menustrucid, $securitymenustructure->menustrucid]);
    }
}
