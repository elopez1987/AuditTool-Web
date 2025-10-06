<?php

namespace App\Models\Shipments;

use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property int $orderid
 * @property string|null $deliveryid
 * @property int $customerid
 * @property string $lpnid
 * @property string $scanuser
 * @property \Illuminate\Support\Carbon $scandate
 * @property string|null $scanuserload
 * @property \Illuminate\Support\Carbon|null $scandateload
 * @property string|null $pathfile
 * @property string $schemaid
 * @property string $statusid
 * @property string|null $closedate
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemStatus $status
 * @property-read User|null $user
 * @property-read User|null $userload
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainer whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainer whereClosedate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainer whereCustomerid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainer whereDeliveryid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainer whereLpnid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainer whereOrderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainer wherePathfile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainer whereScandate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainer whereScandateload($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainer whereScanuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainer whereScanuserload($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainer whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainer whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainer whereWarehouseid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainer withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainer withoutTrashed()
 *
 * @mixin \Eloquent
 */
class ShipmentContainer extends Model
{
    use LogsActivity;
    use SoftDeletes;

    public $timestamps = false;

    protected $table = 'shipmentcontainers';

    protected $fillable = ['centerid', 'warehouseid', 'orderid', 'deliveryid',
        'customerid', 'lpnid', 'scanuser', 'scandate', 'scanuserload',
        'scandateload', 'statusid', 'closedate',
    ];

    protected $hidden = [
        'schemaid',
    ];

    protected $attributes = [
        'statusid' => 'FS',
        'schemaid' => 'RSC',
    ];

    protected $casts = [
        'scandate' => 'datetime:d/m/Y H:s',
        'scandateload' => 'datetime:d/m/Y H:s',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();

        return ShipmentContainer::whereIn('shipmentcontainers.warehouseid', $warehouses)
            ->whereIn('shipmentcontainers.centerid', $centers);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'scanuser', 'username');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function userload()
    {
        return $this->belongsTo(User::class, 'scanuserload', 'username');
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (ShipmentContainer $model) {
                $model->scanuser = \Auth::user()->username;
                $model->scandate = Carbon::now();
            });
        }
    }
}
