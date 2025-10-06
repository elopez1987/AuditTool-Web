<?php

namespace App\Models\Shipments;

use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property int $orderid
 * @property string $prtnum
 * @property int|null $quantity
 * @property string|null $adddate
 * @property string|null $update
 * @property int|null $adduserid
 * @property int|null $lastuserid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read InventoryPartnumber|null $partnumber
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentPallet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentPallet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentPallet query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentPallet whereAdddate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentPallet whereAdduserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentPallet whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentPallet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentPallet whereLastuserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentPallet whereOrderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentPallet wherePrtnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentPallet whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentPallet whereUpdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentPallet whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class ShipmentPallet extends Model
{
    use LogsActivity;

    public $timestamps = false;

    protected $table = 'shipmentpallets';

    protected $primaryKey = 'id';

    protected $fillable = ['centerid', 'warehouseid', 'orderid',
        'prtnum', 'quantity', 'adddate', 'update', 'adduserid', 'lastuserid',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();

        return ShipmentPallet::whereIn('shipmentpallets.warehouseid', $warehouses)
            ->whereIn('shipmentpallets.centerid', $centers);
    }

    public function partnumber()
    {
        return $this->belongsTo(InventoryPartnumber::class, 'prtnum', 'prtnum');
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (ShipmentPallet $model) {
                $model->adddate = Carbon::now();
                $model->adduserid = Auth::user()->id;
            });
        }
    }
}
