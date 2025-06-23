<?php

namespace App\Models\Shipments;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;

class ViewShipmentEvent extends Model
{
    protected $table = 'vw_monitorshipments';

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return ViewShipmentEvent::whereIn('vw_monitorshipments.centerid', $centers);
    }
}
