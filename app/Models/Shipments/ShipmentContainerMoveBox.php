<?php

namespace App\Models\Shipments;

use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property int $orderid
 * @property int $containerid
 * @property string $lpnid
 * @property int $partnumberid
 * @property int $quantity
 * @property string $type
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property int $createuser
 * @property string $created_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read InventoryPartnumber $sku
 * @property-read User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainerMoveBox newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainerMoveBox newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainerMoveBox query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainerMoveBox whereContainerid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainerMoveBox whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainerMoveBox whereCreateuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainerMoveBox whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainerMoveBox whereLpnid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainerMoveBox whereOrderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainerMoveBox wherePartnumberid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainerMoveBox whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainerMoveBox whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainerMoveBox whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainerMoveBox whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainerMoveBox whereValue03($value)
 *
 * @mixin \Eloquent
 */
class ShipmentContainerMoveBox extends Model
{
    use LogsActivity;

    public $timestamps = false;

    protected $table = 'shipmentcontainermoveboxlog';

    protected $fillable = [
        'orderid', 'containerid', 'lpnid', 'partnumberid', 'quantity', 'createuser', 'type',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'createuser', 'id');
    }

    public function sku()
    {
        return $this->belongsTo(InventoryPartnumber::class, 'partnumberid', 'id');
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (ShipmentContainerMoveBox $model) {
                $model->createuser = Auth::id();
                $model->created_at = Carbon::now();
            });
        }
    }
}
