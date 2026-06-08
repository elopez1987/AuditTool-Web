<?php

namespace App\Models\Shipments;

use Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property int $orderid
 * @property int $inverseid
 * @property string|null $delivery
 * @property string|null $requestadjust
 * @property string|null $replyadjust
 * @property string|null $note
 * @property string $username
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property string|null $value04
 * @property string|null $value05
 * @property string $schemaid
 * @property string $statusid
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseInventory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseInventory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseInventory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseInventory whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseInventory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseInventory whereDelivery($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseInventory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseInventory whereInverseid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseInventory whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseInventory whereOrderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseInventory whereReplyadjust($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseInventory whereRequestadjust($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseInventory whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseInventory whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseInventory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseInventory whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseInventory whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseInventory whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseInventory whereValue03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseInventory whereValue04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseInventory whereValue05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageScheduleInverseInventory whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class StorageScheduleInverseInventory extends Model
{
    use LogsActivity;

    protected $table = 'storagescheduleinverseinventory';

    protected $fillable = [
        'centerid', 'warehouseid', 'orderid', 'delivery', 'inverseid', 'username',
        'requestadjust', 'replyadjust', 'note', 'value01', 'value02',
        'value03', 'value04', 'value05',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid',
    ];

    protected $attributes = [
        'statusid' => 'AD',
        'schemaid' => 'LIV',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageScheduleInverseInventory $model) {
                $model->username = Auth::user()->username;
            });
        }
    }
}
