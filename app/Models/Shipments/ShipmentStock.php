<?php

namespace App\Models\Shipments;

use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property int $orderid
 * @property string|null $deliveryid
 * @property int $partnumberid
 * @property int $quantity
 * @property string $lpnid
 * @property string $createuser
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read InventoryPartnumber $partnumber
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentStock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentStock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentStock query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentStock whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentStock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentStock whereCreateuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentStock whereDeliveryid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentStock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentStock whereLpnid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentStock whereOrderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentStock wherePartnumberid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentStock whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentStock whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentStock whereWarehouseid($value)
 * @mixin \Eloquent
 */
class ShipmentStock extends Model
{
    use LogsActivity;

    protected $table = 'shipmentstock';

    protected $fillable = [
        'centerid', 'warehouseid', 'orderid', 'deliveryid', 'partnumberid',
        'quantity', 'lpnid', 'createuser',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public function partnumber()
    {
        return $this->belongsTo(InventoryPartnumber::class, 'partnumberid', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'createuser', 'username');
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (ShipmentStock $model) {
                $model->createuser = Auth::user()->username;
            });
        }
    }
}
