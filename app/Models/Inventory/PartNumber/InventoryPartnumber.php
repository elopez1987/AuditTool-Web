<?php

namespace App\Models\Inventory\PartNumber;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Inventory\Categories\InventoryPartNumberCategory01;
use App\Models\Inventory\Categories\InventoryPartNumberCategory02;
use App\Models\Inventory\UnitMeasure\InventoryUnitMeasure;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property string $prtnum
 * @property string $lngdsc
 * @property int $category01id
 * @property int $category02id
 * @property array<array-key, mixed>|null $configurationflag
 * @property string|null $abccod
 * @property int|null $shelflife
 * @property string|null $stkuom
 * @property float|null $palnetwgt
 * @property float|null $palgrswgt
 * @property int|null $caslvl
 * @property int|null $untcas
 * @property int|null $untpal
 * @property float|null $palwid
 * @property float|null $pallen
 * @property float|null $palhgt
 * @property string|null $barcode
 * @property string|null $codeean
 * @property string|null $codedun
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
 * @property-read InventoryPartNumberCategory01 $category01
 * @property-read InventoryPartNumberCategory02 $category02
 * @property-read SystemCenterDistribution $center
 * @property-read SystemStatus $status
 * @property-read InventoryUnitMeasure|null $unitmeasure
 * @property-read SystemWarehouse $warehouse
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartnumber barcode($barcode)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartnumber newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartnumber newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartnumber partNumber($prtnum)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartnumber query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartnumber status($statusid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartnumber whereAbccod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartnumber whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartnumber whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartnumber whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartnumber whereAuxiliary04($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartnumber whereAuxiliary05($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartnumber whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartnumber whereCaslvl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartnumber whereCategory01id($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartnumber whereCategory02id($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartnumber whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartnumber whereCodedun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartnumber whereCodeean($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartnumber whereConfigurationflag($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartnumber whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartnumber whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartnumber whereLngdsc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartnumber wherePalgrswgt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartnumber wherePalhgt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartnumber wherePallen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartnumber wherePalnetwgt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartnumber wherePalwid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartnumber wherePrtnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartnumber whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartnumber whereShelflife($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartnumber whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartnumber whereStkuom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartnumber whereUntcas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartnumber whereUntpal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartnumber whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryPartnumber whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
class InventoryPartnumber extends Model
{
    use LogsActivity;

    protected $table = 'inventorypartnumbers';

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'category01id',
        'category02id',
        'prtnum',
        'lngdsc',
        'shelflife',
        'stkuom',
        'untcas',
        'untpal',
        'barcode',
        'statusid',
        'codeean',
        'codedun',
        'configurationflag',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
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
        'partnumberid' => 'string',
        'configurationflag' => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();

        return static::whereIn('inventorypartnumbers.warehouseid', $warehouses)
            ->whereIn('inventorypartnumbers.centerid', $centers);
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (InventoryPartnumber $model) {
                //				$model->auxiliary05 = Auth::user()->username;
            });
        }
    }

    public function center(): BelongsTo
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid', 'warehouseid');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function category01(): BelongsTo
    {
        return $this->belongsTo(InventoryPartNumberCategory01::class, 'category01id', 'id');
    }

    public function category02(): BelongsTo
    {
        return $this->belongsTo(InventoryPartNumberCategory02::class, 'category02id', 'id');
    }

    public function unitmeasure()
    {
        return $this->belongsTo(InventoryUnitMeasure::class, 'stkuom', 'unitmeasureid');
    }

    //	Filtros

    public function scopePartNumber($query, $prtnum)
    {
        if (trim($prtnum) != '') {
            $query->where('inventorypartnumbers.prtnum', '=', $prtnum);
        }
    }

    public function scopeBarcode($query, $barcode)
    {
        if (trim($barcode) != '') {
            $query->where('inventorypartnumbers.barcode', '=', $barcode);
        }
    }

    public function scopeStatus($query, $statusid)
    {
        if (empty($statusid)) {
            $query->where('inventorypartnumbers.statusid', 'E');
        } else {
            $query->where('inventorypartnumbers.statusid', $statusid);
        }
    }
}
