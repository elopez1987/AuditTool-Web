<?php

namespace App\Models\Shipments;

use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property int $orderid
 * @property int|null $folionumber
 * @property string $adddate
 * @property string|null $appointment
 * @property string $statusid
 * @property string $schemaid
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read StorageScheduleInverseInventory $inventory
 * @property-read OrderDeliverie $order
 * @property-read SystemStatus $status
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverse forShipment($shipmentid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverse forStatus($statusid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverse query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverse whereAdddate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverse whereAppointment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverse whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverse whereFolionumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverse whereOrderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverse whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverse whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverse whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverse whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class StorageScheduleInverse extends Model
{
    use LogsActivity;

    protected $table = 'storagescheduleinverse';

    protected $primaryKey = 'id';

    protected $fillable = [
        'centerid', 'warehouseid', 'orderid', 'adddate', 'appointment', 'requestzare',
        'zare', 'ne', 'requestne', 'statusid', 'folionumber',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid',
    ];

    protected $attributes = [
        'statusid' => 'AD',
        'schemaid' => 'LIV',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();

        return StorageScheduleInverse::whereIn('storagescheduleinverse.warehouseid', $warehouses)
            ->whereIn('storagescheduleinverse.centerid', $centers);
    }

    public function order()
    {
        return $this->belongsTo(OrderDeliverie::class, 'orderid', 'id');
    }

    public function inventory()
    {
        return $this->belongsTo(StorageScheduleInverseInventory::class, 'orderid', 'orderid');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function scopeForStatus($query, $statusid)
    {
        if ($statusid != '') {
            $query->where('storagescheduleinverse.statusid', $statusid);
        } else {
            $query->where('storagescheduleinverse.statusid', '=', 'AD');
        }
    }

    public function scopeForShipment($query, $shipmentid)
    {
        if ($shipmentid != '') {
            $query->where('orderdeliveries.shipmentid', $shipmentid);
        }
    }

    public static function addNewSchedule($shipment)
    {
        StorageScheduleInverse::updateOrCreate([
            'centerid' => $shipment->centerid,
            'warehouseid' => $shipment->warehouseid,
            'orderid' => $shipment->id,
        ]);

        ScheduleInverseLog::createNewLog(
            $shipment,
            'Creación de registro de incidencia'
        );
    }

    public static function getDataInverse($storagescheduleinverse)
    {
        return DB::table('storagescheduleinverse')
            ->select(
                'orderdeliveries.centerid',
                'orderdeliveries.warehouseid',
                'orderdeliveries.shipmentid',
                'storageincidencejourneydetails.delivery',
                'storageincidencejourneydetails.partnumberid',
                'inventorypartnumbers.lngdsc as description',
                'storageincidencejourneydetails.quantity',
                'storageincidencejourneydetails.unitmeasure',
                'storagecategoryincidencedetails.auxiliary02 as type',
                'inventorypartnumbercategory01.description as div',
                'storagescheduleinversetraffic.typereport',
                'storagescheduleinversetraffic.requestzare',
                'storagescheduleinversetraffic.zare',
                'storagescheduleinversetraffic.responsezare',
                'storagescheduleinversetraffic.responsene',
                'storagescheduleinversetraffic.responsenc',
                'storagescheduleinversetraffic.requestnc',
                'storagescheduleinversetraffic.requestne',
                'storagescheduleinversetraffic.ne',
                'storagescheduleinversetraffic.adjust',
                'storagescheduleinversetraffic.nc',
                'storagescheduleinversetraffic.id as trafic'
            )
            ->leftJoin('orderdeliveries', 'orderdeliveries.id', '=', 'storagescheduleinverse.orderid')
            ->leftJoin('storageincidencejourneydetails', 'orderdeliveries.id', '=', 'storageincidencejourneydetails.orderid')
            ->leftJoin('inventorypartnumbers', 'storageincidencejourneydetails.partnumberid', '=', 'inventorypartnumbers.prtnum')
            ->leftJoin('storagecategoryincidencedetails', 'storageincidencejourneydetails.razoncode', '=', 'storagecategoryincidencedetails.categoryincdetailid')
            ->leftJoin('inventorypartnumbercategory01', 'inventorypartnumbers.category01id', '=', 'inventorypartnumbercategory01.id')
            ->leftJoin('storagescheduleinversetraffic', function ($join) {
                $join->on('storagescheduleinverse.id', '=', 'storagescheduleinversetraffic.inverseid')
                    ->on('storagescheduleinverse.orderid', '=', 'storagescheduleinversetraffic.orderid')
                    ->on('storagescheduleinversetraffic.delivery', '=', 'storageincidencejourneydetails.delivery')
                    ->on('storagescheduleinversetraffic.partnumberid', '=', 'storageincidencejourneydetails.partnumberid');
            })
            ->where('storagescheduleinverse.orderid', '=', $storagescheduleinverse->orderid)
            ->get();
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageScheduleInverse $model) {
                $model->adddate = Carbon::now();
            });
        }
    }
}
