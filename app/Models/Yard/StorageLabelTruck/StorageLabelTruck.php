<?php

namespace App\Models\Yard\StorageLabelTruck;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\Carriers\ShipmentCarrier;
use App\Models\Yard\ShipmentTruck\ShipmentTruck;
use App\Models\Yard\StorageUnitType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string|null $centerid
 * @property string|null $warehouseid
 * @property string $barcode
 * @property int $carrierid
 * @property int|null $truckid
 * @property string $boxnumber
 * @property string|null $econumber
 * @property string $typeid
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string $schemaid
 * @property string $statusid
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read ShipmentCarrier $carrier
 * @property-read SystemCenterDistribution|null $center
 * @property-read SystemStatus $status
 * @property-read ShipmentTruck|null $truck
 * @property-read StorageUnitType|null $unittype
 * @property-read SystemWarehouse|null $warehouse
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLabelTruck boxNumber($boxnumber)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLabelTruck carrier($carrierid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLabelTruck forCarrierOperator($carrier)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLabelTruck label($label)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLabelTruck newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLabelTruck newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLabelTruck query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLabelTruck type($typeid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLabelTruck whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLabelTruck whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLabelTruck whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLabelTruck whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLabelTruck whereBoxnumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLabelTruck whereCarrierid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLabelTruck whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLabelTruck whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLabelTruck whereEconumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLabelTruck whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLabelTruck whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLabelTruck whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLabelTruck whereTruckid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLabelTruck whereTypeid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLabelTruck whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageLabelTruck whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class StorageLabelTruck extends Model
{
    use LogsActivity;

    protected $table = 'storagelabeltrucks';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'barcode',
        'carrierid',
        'truckid',
        'boxnumber',
        'econumber',
        'typeid',
        'statusid',
    ];

    protected $casts = [
        'entrydate' => 'datetime:Y-m-d H:i:s',
        'configurationflag' => 'array',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid',
    ];

    protected $attributes = [
        'schemaid' => 'STS',
        'statusid' => 'E',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    //    Functions filter center for user to model
    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();

        return StorageLabelTruck::whereIn('storagelabeltrucks.centerid', $centers);
    }

    //    Relations with models
    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function carrier()
    {
        return $this->belongsTo(ShipmentCarrier::class, 'carrierid');
    }

    public function truck()
    {
        return $this->belongsTo(ShipmentTruck::class, 'truckid');
    }

    //    Filters to model
    public function scopeLabel($query, $label)
    {
        if (trim($label) != '') {
            $query->where('storagelabeltrucks.barcode', 'like', "%{$label}%");
        }
    }

    public function scopeBoxNumber($query, $boxnumber)
    {
        if (trim($boxnumber) != '') {
            $query->where('storagelabeltrucks.boxnumber', 'like', "%{$boxnumber}%");
        }
    }

    public function scopeType($query, $typeid)
    {
        if (trim($typeid) != '') {
            $query->where('storagelabeltrucks.typeid', $typeid);
        }
    }

    public function scopeCarrier($query, $carrierid)
    {
        if ($carrierid != '') {
            $query->where('storagelabeltrucks.carrierid', $carrierid);
        }
    }

    public function scopeForCarrierOperator($q, $carrier)
    {
        if (! empty($carrier)) {
            $q->whereIn('storagelabeltrucks.carrierid', $carrier);
        }
    }
}
