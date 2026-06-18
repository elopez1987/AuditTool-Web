<?php

namespace App\Models\Shipments\Bridgestone\Verificacion;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Yard\StorageLocation;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property int|null $folioid
 * @property int $orderid
 * @property int $locationid
 * @property int $quantity
 * @property string|null $name
 * @property string|null $keyoperator
 * @property int $userid
 * @property string|null $keyguard
 * @property string|null $keysupervisor
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution $center
 * @property-read StorageLocation $location
 * @property-read OrderDeliverie $order
 * @property-read User|null $user
 * @property-read SystemWarehouse $warehouse
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentCheckTotal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentCheckTotal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentCheckTotal query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentCheckTotal whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentCheckTotal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentCheckTotal whereFolioid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentCheckTotal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentCheckTotal whereKeyguard($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentCheckTotal whereKeyoperator($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentCheckTotal whereKeysupervisor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentCheckTotal whereLocationid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentCheckTotal whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentCheckTotal whereOrderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentCheckTotal whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentCheckTotal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentCheckTotal whereUserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentCheckTotal whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentCheckTotal whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentCheckTotal whereValue03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentCheckTotal whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class StorageShipmentCheckTotal extends Model
{
    use LogsActivity;

    protected $table = 'storageshipmentchecktotal';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'orderid',
        'locationid',
        'quantity',
        'userid',
        'value01',
        'value02',
        'value03',
        'keyoperator',
        'keyguard',
        'keysupervisor',
        'name',
        'team',
        'tunrid',
        'supervisorname',
        'supervisorkey',
        'folioid',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();

        return StorageShipmentCheckTotal::whereIn('storageshipmentchecktotal.warehouseid', $warehouses)
            ->whereIn('storageshipmentchecktotal.centerid', $centers);
    }

    public function center(): BelongsTo
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(OrderDeliverie::class, 'orderid');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(StorageLocation::class, 'locationid');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userid', 'id');
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageShipmentCheckTotal $model) {
                $model->userid = Auth::id();
                $model->created_at = Carbon::now();
            });
        }
    }
}
