<?php

namespace App\Models\Shipments\Bridgestone\Verificacion;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Turns\StorageTurn;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Yard\StorageLocation;
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
 * @property string $warehouseid
 * @property Carbon|null $workday
 * @property int $orderid
 * @property int $turnid
 * @property int $locationid
 * @property int|null $sobranteizq
 * @property int|null $ncizq
 * @property int|null $totalIzquierdo
 * @property array<array-key, mixed>|null $detalleizquierdo
 * @property int|null $sobranteder
 * @property int|null $ncder
 * @property int|null $totalderecho
 * @property array<array-key, mixed>|null $detallederecho
 * @property int|null $totalfinal
 * @property string|null $name
 * @property string|null $value01
 * @property string|null $value02
 * @property string|null $value03
 * @property-read Collection<int, Activity> $activities
 * @property-read int|null $activities_count
 * @property-read SystemCenterDistribution|null $center
 * @property-read StorageLocation|null $location
 * @property-read OrderDeliverie $order
 * @property-read StorageTurn|null $turn
 * @property-read SystemWarehouse $warehouse
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoragechecklocationbridgVig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoragechecklocationbridgVig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoragechecklocationbridgVig query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoragechecklocationbridgVig whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoragechecklocationbridgVig whereDetallederecho($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoragechecklocationbridgVig whereDetalleizquierdo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoragechecklocationbridgVig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoragechecklocationbridgVig whereLocationid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoragechecklocationbridgVig whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoragechecklocationbridgVig whereNcder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoragechecklocationbridgVig whereNcizq($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoragechecklocationbridgVig whereOrderid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoragechecklocationbridgVig whereSobranteder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoragechecklocationbridgVig whereSobranteizq($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoragechecklocationbridgVig whereTotalIzquierdo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoragechecklocationbridgVig whereTotalderecho($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoragechecklocationbridgVig whereTotalfinal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoragechecklocationbridgVig whereTurnid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoragechecklocationbridgVig whereValue01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoragechecklocationbridgVig whereValue02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoragechecklocationbridgVig whereValue03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoragechecklocationbridgVig whereWarehouseid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoragechecklocationbridgVig whereWorkday($value)
 *
 * @mixin \Eloquent
 */
class StoragechecklocationbridgVig extends Model
{
    use LogsActivity;

    public $timestamps = false;

    protected $table = 'storagechecklocationbridgvig';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'workday',
        'orderid',
        'turnid',
        'locationid',
        'totalizquierdo',
        'totalderecho',
        'totalfinal',
        'detalleizquierdo',
        'detallederecho',
        'name',
        'value01',
        'value02',
        'value03',
        'ncizq',
        'sobranteizq',
        'sobranteder',
        'ncder',
        'model_type',
    ];

    protected $casts = [
        'workday' => 'date',
        'detalleizquierdo' => 'array',
        'detallederecho' => 'array',

    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();

        return StoragechecklocationbridgVig::whereIn('storagechecklocationbridgvig.warehouseid', $warehouses)
            ->whereIn('storagechecklocationbridgvig.centerid', $centers);
    }

    public function center(): BelongsTo
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid', 'warehouseid');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(OrderDeliverie::class, 'orderid');
    }

    public function location()
    {
        return $this->belongsTo(StorageLocation::class, 'locationid', 'id');
    }

    public function turn()
    {
        return $this->belongsTo(StorageTurn::class, 'turnid', 'id');
    }
}
