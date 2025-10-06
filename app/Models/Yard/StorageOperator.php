<?php

namespace App\Models\Yard;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\Carriers\ShipmentCarrier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $barcode
 * @property string $name
 * @property string|null $drivernumber
 * @property string|null $phone
 * @property string|null $configurationflag
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string|null $auxiliary04
 * @property string|null $auxiliary05
 * @property string $schemaid
 * @property string $statusid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $carrierid
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read ShipmentCarrier|null $carrier
 * @property-read SystemCenterDistribution $center
 * @property-read SystemStatus $status
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageOperator barcode($barcode)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageOperator forCarrierOperator($carrier)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageOperator name($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageOperator newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageOperator newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageOperator query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageOperator whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageOperator whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageOperator whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageOperator whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageOperator whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageOperator whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageOperator whereCarrierid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageOperator whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageOperator whereConfigurationflag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageOperator whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageOperator whereDrivernumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageOperator whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageOperator whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageOperator wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageOperator whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageOperator whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorageOperator whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class StorageOperator extends Model
{
    use LogsActivity;

    protected $table = 'storageoperators';

    protected $primaryKey = 'id';

    protected $keyType = 'integer';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid',
        'barcode',
        'carrierid',
        'drivernumber',
        'phone',
        'name',
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
        'barcode' => 'string',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    //    Relations with models
    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function carrier()
    {
        return $this->belongsTo(ShipmentCarrier::class, 'carrierid', 'id');
    }

    //    Filters Scope to model
    public function scopeName($query, $name)
    {
        if (trim($name) !== '') {
            $query->where('storageoperators.name', 'like', "%{$name}%");
        }
    }

    public function scopeBarcode($query, $barcode)
    {
        if (trim($barcode) !== '') {
            $query->where('storageoperators.barcode', '=', $barcode);
        }
    }

    public function scopeForCarrierOperator($q, $carrier)
    {
        if (! empty($carrier)) {
            $q->whereIn('storageoperators.carrierid', $carrier);
        }
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();

        return StorageOperator::whereIn('storageoperators.centerid', $centers);
    }
}
