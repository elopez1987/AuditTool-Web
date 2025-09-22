<?php

namespace App\Models\Shipments\ScheduleInverse;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ShipmentSchedulleDetail extends Model
{
    use LogsActivity;

    public $timestamps = false;
    protected $table = 'storageshipmentsscheduledetail';
    protected $fillable = ['centerid', 'warehouseid', 'incidencejourneyid','shipmentschedulleid'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return ShipmentSchedulleDetail::whereIn('storageshipmentsscheduledetail.warehouseid', $warehouses)
            ->whereIn('storageshipmentsscheduledetail.centerid', $centers);
    }
}
