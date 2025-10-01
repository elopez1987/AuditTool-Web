<?php

namespace App\Models\Shipments;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property int $orderid
 * @property int $containerid
 * @property string $lpnid
 * @property string|null $lpnchild
 * @property int $createuser
 * @property string|null $created_at
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property string|null $value04
 * @property string|null $value05
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainerCheck newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainerCheck newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainerCheck query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainerCheck whereContainerid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainerCheck whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainerCheck whereCreateuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainerCheck whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainerCheck whereLpnchild($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainerCheck whereLpnid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainerCheck whereOrderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainerCheck whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainerCheck whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainerCheck whereValue03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainerCheck whereValue04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShipmentContainerCheck whereValue05($value)
 * @mixin \Eloquent
 */
class ShipmentContainerCheck extends Model
{
    use LogsActivity;
    public $timestamps = false;
    protected $table = 'shipmentcontainercheck';

    protected $fillable = [
        'orderid','lpnid','containerid','lpnchild','createuser','created_at','orderid',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'createuser', 'id');
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (ShipmentContainerCheck $model) {
                $model->createuser = \Auth::id();
                $model->created_at = Carbon::now();
            });
        }
    }
}
