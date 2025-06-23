<?php

namespace App\Models\Yard;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;

class StorageBoxTravel extends Model
{
    protected $table = 'vw_storageboxtravels';

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return StorageBoxTravel::whereIn('vw_storageboxtravels.centerid', $centers);
    }

    public function scopeShipmentid($query, $shipmentid)
    {
        if (trim($shipmentid) != "") {
            $query->where('vw_storageboxtravels.shipmentid', 'like', "%$shipmentid%");
        }
    }

    public function scopeBoxNumber($query, $boxnumber)
    {
        if (trim($boxnumber) != '') {
            $query->where('vw_storageboxtravels.boxnumber', 'like', "%$boxnumber%");
        }
    }

    public function scopeFolio($query, $folionumber)
    {
        if (trim($folionumber) != "") {
            $query->where('vw_storageboxtravels.folioplant', '=', $folionumber);
        }
    }

    public function scopeFoliowm($query, $folionumberwm)
    {
        if (trim($folionumberwm) != "") {
            $query->where('vw_storageboxtravels.foliowarehouse', '=', $folionumberwm);
        }
    }


}
