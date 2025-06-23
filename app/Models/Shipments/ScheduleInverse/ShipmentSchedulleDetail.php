<?php

namespace App\Models\Shipments\ScheduleInverse;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;

class ShipmentSchedulleDetail extends Model
{
    public $timestamps = false;
    protected $table = 'storageshipmentsscheduledetail';
    protected $fillable = ['centerid', 'warehouseid', 'incidencejourneyid','shipmentschedulleid'];


    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return ShipmentSchedulleDetail::whereIn('storageshipmentsscheduledetail.warehouseid', $warehouses)
            ->whereIn('storageshipmentsscheduledetail.centerid', $centers);
    }




}
