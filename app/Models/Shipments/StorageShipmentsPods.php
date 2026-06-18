<?php

namespace App\Models\Shipments;

use App\Models\Configuration\Schemas\SystemStatus;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $recordid
 * @property string $centerid
 * @property string $warehouseid
 * @property int $orderid
 * @property string $pathfile
 * @property string $createuser
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property string|null $schemaid
 * @property string|null $statusid
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read OrderDeliverie $order
 * @property-read SystemStatus|null $status
 * @property-read User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentsPods newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentsPods newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentsPods query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentsPods whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentsPods whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentsPods whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentsPods whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentsPods whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentsPods whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentsPods whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentsPods whereCreateuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentsPods whereOrderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentsPods wherePathfile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentsPods whereRecordid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentsPods whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentsPods whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentsPods whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageShipmentsPods whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class StorageShipmentsPods extends Model
{
    protected $table = 'storageshipmentspods';

    protected $primaryKey = 'recordid';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'orderid',
        'pathfile',
        'createuser',
        'statusid',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid',
    ];

    protected $attributes = [
        'statusid' => 'PED',
        'schemaid' => 'POD',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'createuser', 'username');
    }

    public function order()
    {
        return $this->belongsTo(OrderDeliverie::class, 'orderid', 'id');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }
}
