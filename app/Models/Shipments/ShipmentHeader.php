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
