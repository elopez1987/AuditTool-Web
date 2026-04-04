<?php

namespace App\Models\Yard\Customer;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @method static whereStatusid(string $string)
 *
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property string|null $customerid
 * @property string|null $shiptoid
 * @property string $name
 * @property string|null $city
 * @property int|null $timetransfer
 * @property int|null $timebulkassembly
 * @property int|null $timearmedpick
 * @property int|null $timecheck
 * @property int|null $timeload
 * @property int|null $timebilling
 * @property array<array-key, mixed>|null $configurationflag
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property string $schemaid
 * @property string $statusid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution $center
 * @property-read SystemStatus $status
 * @property-read SystemWarehouse $warehouse
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderCustomer name($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderCustomer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderCustomer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderCustomer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderCustomer shipto($shiptoid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderCustomer warehouse($warehouseid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderCustomer whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderCustomer whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderCustomer whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderCustomer whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderCustomer whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderCustomer whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderCustomer whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderCustomer whereConfigurationflag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderCustomer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderCustomer whereCustomerid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderCustomer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderCustomer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderCustomer whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderCustomer whereShiptoid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderCustomer whereTimearmedpick($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderCustomer whereTimebilling($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderCustomer whereTimebulkassembly($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderCustomer whereTimecheck($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderCustomer whereTimeload($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderCustomer whereTimetransfer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderCustomer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderCustomer whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class OrderCustomer extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected $table = 'ordercustomers';

    protected $primaryKey = 'id';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'name',
        'city',
        'customerid',
        'shiptoid',
        'timetransfer',
        'timebulkassembly',
        'timearmedpick',
        'timecheck',
        'timeload',
        'timebilling',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'statusid',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid',
    ];

    protected $attributes = [
        'statusid' => 'E',
        'schemaid' => 'STS',
    ];

    protected $casts = [
        'shiptoid' => 'string',
        'configurationflag' => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public function getCity($value)
    {
        return strtoupper($value);
    }

    public function getNameAttribute($value)
    {
        return strtoupper($value);
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();

        return OrderCustomer::whereIn('ordercustomers.centerid', $centers)
            ->whereIn('ordercustomers.warehouseid', $warehouses);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid');
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function scopeName($query, $name)
    {
        if (trim($name) != '') {
            $query->where('ordercustomers.name', 'like', "%{$name}%");
        }
    }

    public function scopeShipto($query, $shiptoid)
    {
        if (trim($shiptoid) != '') {
            $query->where('ordercustomers.shiptoid', 'like', "%{$shiptoid}%");
        }
    }

    public function scopeWarehouse($query, $warehouseid)
    {
        if (trim($warehouseid) != '') {
            $query->where('ordercustomers.warehouseid', '=', $warehouseid);
        }
    }
}
