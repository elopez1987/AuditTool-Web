<?php

namespace App\Models\Shipments\ScheduleInverse;

use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\Carriers\ShipmentCarrier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property string $centerid
 * @property string $warehouseid
 * @property int $id
 * @property int $carrierid
 * @property string|null $appointment
 * @property int $createuser
 * @property string|null $token
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property string|null $schemaid
 * @property string|null $statusid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read ShipmentCarrier $carrier
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentSchedule carrierIn($carrierid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentSchedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentSchedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentSchedule query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentSchedule whereAppointment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentSchedule whereCarrierid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentSchedule whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentSchedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentSchedule whereCreateuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentSchedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentSchedule whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentSchedule whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentSchedule whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentSchedule whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentSchedule whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentSchedule whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentSchedule whereValue03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentSchedule whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
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
