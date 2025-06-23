<?php

namespace App\Models\Inventory\FiscalInventory\Locations;

use Illuminate\Database\Eloquent\Model;

class MonitorLocationInventory extends Model
{

    protected $table = 'vw_monitorlocationinventory';

    public function scopeCard($query, $cardid)
    {
        if (trim($cardid) != "") {
            $query->where('vw_monitorlocationinventory.cardid', '=', $cardid);
        }
    }


    public function scopeAisle($query, $aisleid)
    {
        if (trim($aisleid) != "") {
            $query->where('vw_monitorlocationinventory.aisleid', '=', $aisleid);
        }
    }

}
