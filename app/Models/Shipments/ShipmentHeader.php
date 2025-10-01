<?php

namespace App\Models\Shipments;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\StorageLocation;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @method static create(array $array)
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property int $orderid
 * @property string|null $delivery
 * @property string $adddate
 * @property string|null $startdate
 * @property string|null $finishdate
 * @property string|null $startgoodissuedate
 * @property string|null $goodissuedate
 * @property string|null $cancellationdate
 * @property float|null $totalweight
 * @property float|null $totalvolume
 * @property int|null $totalpieces
 * @property int|null $totalcontainers
 * @property int|null $totalpiecesfac
 * @property int|null $totalcontainersfac
 * @property string|null $locationid
 * @property string|null $comment
 * @property int|null $reasonoutputid
 * @property int|null $createuserid
 * @property string $schemaid
 * @property string $statusid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution $center
 * @property-read StorageLocation|null $location
 * @property-read \App\Models\Shipments\ShipmentDataOperation $operation
 * @property-read \App\Models\Shipments\ShipmentSeal $seal
 * @property-read \App\Models\Shipments\OrderDeliverie $shipment
 * @property-read SystemStatus $status
 * @property-read User|null $user
 * @property-read SystemWarehouse $warehouse
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentHeader forShipment($shipmentid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentHeader newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentHeader newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentHeader query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentHeader whereAdddate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentHeader whereCancellationdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentHeader whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentHeader whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentHeader whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentHeader whereCreateuserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentHeader whereDelivery($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentHeader whereFinishdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentHeader whereGoodissuedate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentHeader whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentHeader whereLocationid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentHeader whereOrderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentHeader whereReasonoutputid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentHeader whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentHeader whereStartdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentHeader whereStartgoodissuedate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentHeader whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentHeader whereTotalcontainers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentHeader whereTotalcontainersfac($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentHeader whereTotalpieces($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentHeader whereTotalpiecesfac($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentHeader whereTotalvolume($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentHeader whereTotalweight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentHeader whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentHeader whereWarehouseid($value)
 * @mixin \Eloquent
 */
class ShipmentHeader extends Model
{
    use LogsActivity;

    protected $table = 'shipmentheader';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid', 'warehouseid', 'orderid', 'delivery', 'adddate', 'startdate', 'finishdate',
        'locationid', 'totalpiecesfac', 'totalcontainersfac', 'reasonoutputid', 'cancellationdate',
        'startgoodissuedate', 'goodissuedate', 'totalweight', 'createuserid', 'totalvolume',
        'totalpieces', 'totalcontainers', 'statusid', 'comment',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $attributes = [
        'statusid' => 'AP',
        'schemaid' => 'KLE',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();
        return ShipmentHeader::whereIn('shipmentheader.centerid', $centers)
            ->whereIn('shipmentheader.warehouseid', $warehouses);
    }

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(OrderDeliverie::class, 'orderid', 'id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function warehouse()
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid');
    }

    public function operation()
    {
        return $this->belongsTo(ShipmentDataOperation::class, 'orderid', 'orderid');
    }

    public function location()
    {
        return $this->belongsTo(StorageLocation::class, 'locationid', 'id');
    }

    public function seal()
    {
        return $this->belongsTo(ShipmentSeal::class, 'orderid', 'orderid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'createuserid', 'id');
    }

    public function scopeForShipment($query, $shipmentid)
    {
        if (trim($shipmentid) !== '') {
            $query->where('orderdeliveries.shipmentid', '=', $shipmentid);
        }
    }
}
