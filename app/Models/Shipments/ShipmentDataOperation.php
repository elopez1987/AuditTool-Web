<?php

namespace App\Models\Shipments;

use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property int $orderid
 * @property string|null $supervisor
 * @property string|null $checker
 * @property string|null $forklift
 * @property string|null $startshipment
 * @property string|null $endchecker
 * @property string|null $forload
 * @property string|null $startload
 * @property string|null $endload
 * @property string|null $senddocument
 * @property string|null $requestletter
 * @property string|null $responseletter
 * @property string|null $fullpallet
 * @property string|null $fullpicking
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property string|null $comment01
 * @property string|null $comment02
 * @property string|null $comment03
 * @property string|null $comment04
 * @property string|null $comment05
 * @property int|null $createuserid
 * @property int|null $lastuserid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Shipments\OrderDeliverie $order
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Shipments\ShipmentPallet> $pallets
 * @property-read int|null $pallets_count
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentDataOperation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentDataOperation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentDataOperation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentDataOperation whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentDataOperation whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentDataOperation whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentDataOperation whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentDataOperation whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentDataOperation whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentDataOperation whereChecker($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentDataOperation whereComment01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentDataOperation whereComment02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentDataOperation whereComment03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentDataOperation whereComment04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentDataOperation whereComment05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentDataOperation whereCreateuserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentDataOperation whereEndchecker($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentDataOperation whereEndload($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentDataOperation whereForklift($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentDataOperation whereForload($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentDataOperation whereFullpallet($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentDataOperation whereFullpicking($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentDataOperation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentDataOperation whereLastuserid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentDataOperation whereOrderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentDataOperation whereRequestletter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentDataOperation whereResponseletter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentDataOperation whereSenddocument($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentDataOperation whereStartload($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentDataOperation whereStartshipment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentDataOperation whereSupervisor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentDataOperation whereWarehouseid($value)
 * @mixin \Eloquent
 */
class ShipmentDataOperation extends Model
{
    use LogsActivity;
    public $timestamps = false;
    protected $table = 'shipmentdataoperation';
    protected $primaryKey = 'id';

    protected $fillable = ['centerid', 'warehouseid', 'orderid', 'supervisor', 'checker',
        'forklift', 'startshipment', 'startchecker', 'endchecker', 'startload', 'endload',
        'closematerial', 'senddocument', 'auxiliary01', 'auxiliary02', 'auxiliary03', 'auxiliary04', 'auxiliary05', 'comment01',
        'comment02', 'comment03', 'comment04', 'comment05', 'fullpallet', 'fullpickng',
        'createuserid', 'lastuserid', 'forload',
    ];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();
        return ShipmentDataOperation::whereIn('centerid', $centers)
            ->whereIn('warehouseid', $warehouses);
    }

    public function pallets()
    {
        return $this->hasMany(ShipmentPallet::class, 'orderid', 'orderid');
    }

    public function order()
    {
        return $this->belongsTo(OrderDeliverie::class, 'orderid', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'lastuserid', 'id');
    }
}
