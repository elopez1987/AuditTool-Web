<?php

namespace App\Models\Yard;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;

class ReportUnitEvidence extends Model
{
    protected $table = 'vw_storageunitevidence';

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return ReportUnitEvidence::whereIn('vw_storageunitevidence.centerid', $centers);
    }

}
