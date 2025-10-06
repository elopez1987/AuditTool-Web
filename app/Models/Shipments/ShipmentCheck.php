<?php

namespace App\Models\Shipments;

use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $recordid
 * @property string $centerid
 * @property string $warehouseid
 * @property int $orderid
 * @property string|null $startdate
 * @property string|null $finishdate
 * @property string|null $createuser
 * @property string|null $schemaid
 * @property string|null $statusid
 * @property-read SystemStatus|null $status
 * @property-read User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentCheck newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentCheck newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentCheck query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentCheck whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentCheck whereCreateuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentCheck whereFinishdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentCheck whereOrderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentCheck whereRecordid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentCheck whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentCheck whereStartdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentCheck whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentCheck whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class ShipmentCheck extends Model
{
    public $timestamps = false;

    protected $table = 'shipmentcheck';

    protected $primaryKey = 'recordid';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'orderid',
        'quantityrequested',
        'quantitytrue',
        'startdate',
        'finishdate',
        'createuser',
        'statusid',
    ];

    protected $attributes = [
        'schemaid' => 'KLE',
        'statusid' => 'AP',
    ];

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();

        return ShipmentCheck::whereIn('shipmentcheck.warehouseid', $warehouses)
            ->whereIn('shipmentcheck.centerid', $centers);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'createuser', 'username');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }
}
