<?php

namespace App\Models\Shipments\ScheduleInverse;

use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\Carriers\ShipmentCarrier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StorageShipmentSchedule extends Model
{
    use LogsActivity;

    protected $table = 'storageshipmentsschedule';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'carrierid',
        'appointment',
        'createuser',
        'statusid',
        'token', 'value01', 'value02', 'value03',
    ];

//    AGD = AGENDADO
//    ENT = ENTREGADO
    protected $attributes = [
        'statusid' => 'AP',
        'schemaid' => 'DEC',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return StorageShipmentSchedule::whereIn('storageshipmentsschedule.warehouseid', $warehouses)
            ->whereIn('storageshipmentsschedule.centerid', $centers);
    }

    public function carrier()
    {
        return $this->belongsTo(ShipmentCarrier::class, 'carrierid');
    }

    public function scopeCarrierIn($query, $carrierid)
    {
        if (! empty($carrierid)) {
            $query->whereIn('storageshipmentsschedule.carrierid', $carrierid);
        }
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageShipmentSchedule $model) {
                $model->createuser = Auth::id();
                $model->token = bin2hex(random_bytes(10));
            });
        }
    }
}
